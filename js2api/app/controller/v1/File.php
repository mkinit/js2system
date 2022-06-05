<?php

namespace app\controller\v1;

use app\common\Api;
use app\common\model\File as FileModel;
use app\common\Tools;
use Intervention\Image\ImageManagerStatic as Image;
use think\exception\ValidateException;
use think\Request;

class File extends Api {
	/*
		@param	$keywords	String	关键词搜索，空格分隔
		@param	$page		Number	分页
		@param	$page_size	Number	分页条数
	*/
	public function index($keywords = '') {
		$setting_info = $this->setting_info; //获取系统设置信息

		$where = [];
		if ($keywords) {
			$keywords = explode(' ', $keywords);
			foreach ($keywords as $key => $value) {
				array_push($where, ['desc', 'like', '%' . $value . '%']);
			}
		}

		$files = FileModel::whereOr($where)->page($this->page, $this->pageSize)->order('time_add desc')->select();

		//缩略图
		foreach ($this->thumbnail_size as $key => $size) {
			foreach ($files as $file) {
				if ($file['type'] != 'image') {
					continue;
				}

				$name_arr = explode('.', $file['name']);
				$file[$key] = $name_arr[0] . '_' . $size . '.' . $name_arr[1];
			}
		}
		$pagination = [
			'total' => FileModel::where($where)->count(),
		];
		$this->setPagination($pagination);
		return $this->response($files);
	}

	public function update($id, $desc) {
		$file = FileModel::find($id);
		if (!$file) {
			return $this->response(null, '没有该内容', 404);
		}
		$file->desc = $desc;
		$result = $file->save();
		if ($result) {
			return $this->response($file);
		}
	}

	public function delete($ids) {
		$ids_arr = explode(',', $ids);
		$files = FileModel::select($ids_arr);
		$setting_info = $this->setting_info; //获取系统设置信息
		foreach ($files as $file) {
			$ext = pathinfo($file['name'])['extension'];
			//如果是图片，需要删除缩略图
			if (Tools::isImage($ext)) {
				$file_name_arr = explode('.', $file['name']);
				foreach ($this->thumbnail_size as $size) {
					$file_path = $file_name_arr[0] . '_' . $size . '.' . $ext;
					@unlink(public_path() . $file_path);
				}

			}
			@unlink(public_path() . $file['name']);
		}
		FileModel::destroy($ids_arr);
		return $this->response();
	}

	/*
		@param	$file	二进制文件*
		@param	$folder	上传目录，前台用户上传指定目录
	*/
	public function upload($folder = '') {
		$setting_info = $this->setting_info;
		$file = $this->request->file('file');

		if (!$file) {
			return $this->response(null, '请选择需要上传的文件', 400);
		}

		$host = '/';
		$save_path = $folder ? 'upload/' . $folder : 'upload'; //保存路径
		$date = date_format(date_create(), 'YmdHis'); //当前日期，用作文件名
		$upload_limit = (int) $setting_info['upload_limit'] * 1024 * 1024; //上传大小限制转化为字节

		//单文件上传
		if (gettype($file) === 'object') {
			try {
				validate(['file' => 'fileSize:' . $upload_limit])
					->check(['file' => $file]);
			} catch (ValidateException $e) {
				return $this->response(null, $e->getMessage(), 400);
			}

			$file_name = $file->getOriginalName();
			if (empty(pathinfo($file_name)['extension'])) {
				return $this->response(null, '请上传常规文件', 400);
			}
			$ext = '.' . pathinfo($file_name)['extension']; //提取文件扩展名
			$save_name = \think\facade\Filesystem::putFileAs($save_path, $file, $date . $ext);

			//记录到数据库
			$file_model = new FileModel;
			$file_model->name = $host . $save_name;
			$file_model->desc = pathinfo($file_name)['filename'];

			//如果是图片
			if (Tools::isImage($ext)) {
				$file_model->type = 'image';
				$file_model->save();
				$response = $file_model->toArray(); //响应数据
				//生成缩略图
				foreach ($this->thumbnail_size as $key => $size) {
					$img = Image::make($save_name);
					$img->resize($size, null, function ($constraint) {
						$constraint->aspectRatio();
					});
					$path = $save_path . '/' . $date . '_' . $size . $ext;
					$img->save($path);
					$response[$key] = $host . $path; //添加缩略图数据
				}
				return $this->response($response);
			} else {
				if (Tools::isVideo($ext)) {
					$file_model->type = 'video';
				} else if (Tools::isAudio($ext)) {
					$file_model->type = 'audio';
				} else {
					$file_model->type = 'file';
				}
				$file_model->save();
			}

			return $this->response($file_model);
		}

		//多文件上传
		if (gettype($file) === 'array') {
			$file_filter = []; //过滤不符合尺寸要求的文件
			$error_index = []; //不符合尺寸要求文件的序号
			foreach ($file as $key => $item) {
				try {
					validate(['file' => 'fileSize:' . $upload_limit])
						->check(['file' => $item]);
					$file_filter[] = $item;
				} catch (ValidateException $e) {
					$error_index[] = $key + 1;
					//return $this->response(null, $e->getMessage(), 400);
				}
			}
			$response_data = []; //响应的结果
			foreach ($file_filter as $index => $item) {
				$file_name = $item->getOriginalName();
				if (empty(pathinfo($file_name)['extension'])) {
					return $this->response(null, '请上传常规文件', 400);
				}
				$ext = '.' . pathinfo($file_name)['extension']; //提取文件扩展名
				$save_name = \think\facade\Filesystem::putFileAs($save_path, $item, $date . '-' . ($index + 1) . $ext);

				//记录到数据库
				$file_model = new FileModel;
				$file_model->name = $host . $save_name;
				$file_model->desc = pathinfo($file_name)['filename'];

				//如果是图片
				if (Tools::isImage($ext)) {
					$file_model->type = 'image';
					$file_model->save();
					$file_model->id = (int) $file_model->id;
					$response = $file_model->toArray(); //响应数据
					//生成缩略图
					foreach ($this->thumbnail_size as $key => $value) {
						$img = Image::make($save_name);
						$img->resize($value, null, function ($constraint) {
							$constraint->aspectRatio();
						});
						$path = $save_path . '/' . $date . '-' . ($index + 1) . '_' . $value . $ext;
						$img->save($path);
						$response[$key] = $host . $path; //添加缩略图数据
					}
					array_push($response_data, $response);
				} else {
					if (Tools::isVideo($ext)) {
						$file_model->type = 'video'; //视频文件
					} else if (Tools::isAudio($ext)) {
						$file_model->type = 'audio'; //音频文件
					} else {
						$file_model->type = 'file'; //其他文件
					}
					$file_model->save();
					$file_model->id = (int) $file_model->id;
					array_push($response_data, $file_model);
				}
			}
			$msg = count($error_index) ? '第 ' . implode('、', $error_index) . ' 个文件不符合尺寸要求，请修改尺寸后重新上传' : '上传成功';
			return $this->response($response_data, $msg);
		}
	}
}

const base_url = process.env.NODE_ENV=='development'?'https://js2api.com':'https://demo.mkinit.com'
const config = {
	base_url,
	api_url: base_url + '/js2-json/v1'
}
module.exports = config
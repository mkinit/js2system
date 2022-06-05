//import https from 'https';
export default function({ $axios, redirect, store }) {
	//$axios.defaults.httpsAgent = new https.Agent({ rejectUnauthorized: false });
	$axios.onRequest(config => {
		//console.log(config)
		config.headers = {
			token: store.state.user?store.state.user.token:''
		}
	})

	$axios.onError(error => {
		//console.log(error)
	})
}

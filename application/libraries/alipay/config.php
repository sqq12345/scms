<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2018050302625459",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCNmgx89pap8Sv0s5t8ModwqPFoUA+gpplQhrtl1+wOL3cNHWzSSZNbJOGuSVRl8ZcC3HkiwrM1vBmf+LJE1S9Ip85pHAv+G8pfoQB0ZM5Tn3LUC2AhYNPnb+bom0Iwi2bGp8lcaHjnoNWOaFLbX3+RndqM4LUiaMordeqYflf1j75n+bmb+MGVgylZzTtatmptZzfgBE2j9ewaxrjCOfs7sUN1AIluFEQoTIWMun0Lb0lWDg0TIn+OssLvEvOg5Puvy0RPI5NGNV9UUjKGQRpX9CUMx71P35qTfwNhvPlBPSR64vbxrhrthYeLr6ew73aOPSS+re0enMrj75ebvv2nAgMBAAECggEAX5559lyRqtpnw0sRoNGCMipzMex2URaPCxigLQqcpYuZyepn1KzIa9DA8O8lpd15Cv6UckultpB5gVPwFZkb3+Uo9kNxObvMcb0H0JFN3pwab2PrGz8GeQ9QjxxgmuVXlqgwykzl8AvkidCauvaG72736Q/IYR7//k8XBriybugISR9DTYABBJmi1pkX2f4q/tnN427I+K6wAvz9FIEoPMSKmWxb/KvusdWQJZr6/cVcdIDzM87vOt+9cNbsSJjTFNyKhNGgAyceaJK0jZ+uVKwrrY2lc3kEonLt+ySMqtrYUvhCj9w/2k9mhANGsrZqNA85cStwnLDWWZcrE6i6WQKBgQDiINP3Vwj89N4bbtQ1mKz2yfM+K1qnzWR/PjEoJSgzpl9RM1YDYv4d1e5lcp1dDvmP+gQp3LBcRxxcamHhWaMBCkXA5xZgXu7dfcB0V8RxzmWLFmhx6HboBxvzzi+dy66lY2lRmXoUuzGQjK3j14Ox6F8O0GCuv10G9kJhrl0fJQKBgQCgTrdohY4jLhZfjiz4zjyVFdQT0nMkdisx3CrxE11D3fiMUE6sJYvHUN4ypA9L0WWW30dRB66QagGidhy8Fvj6g+0ql6/375fOqn1T/xv301lYiKKrHb6Tax81yhY8I4QCAV5ePp/i9vzNeLGzhmqQwEKsEzzJnSF4ilD7ZLEl2wKBgQCEX81WJNg5JKuFCasmuPq/+dbwVPbb9ovXRTQHmUDgg4uXAGNg1imGk77cGm1ulZ6YnzaivvNrAaHjo88q2Ytnx3iwBVd/EPPqK3xnXx27taSR+Isp63j4OXkuj0wmpp7VaM21nA/wZOkOApylHXuVwT8sb+W5RoMR2UVg427WFQKBgFxCETHZMx0yB/REacNjReBzKJOj2VpRm7hdQmVtxI4rcECocy6FiTVTaB1y5861mybCJ1QN/LvmFjy+hvkEq5PZWyPZGo+xVwe8fNZbimgNPW1DbYLXYneK/fJB9Jv1wKI60Wmh1viTNpi17mcoY6oczAImLCTIBpxN1h9oKr9jAoGBANXQ5ZO8u0es/mBsuIW2k7Yy7CdyZpc/iJPv25JmWkeqCBQPTkAyFVAY7QwfzdBRSgGGKi5DdGnZJxmj8olRDWqTVRZZZNr7M0iU8X1LiU6nMnrlZSsxEyF/pml1ea6hybNuddEbvTingt75iqnIaQQwWi/ZnEjZXiNH1HRnSgMR",
		//异步通知地址
		'notify_url' => "https://api.shichamaishou.com/application/libraries/alipay/notify_url.php",

		//同步跳转
		'return_url' => "https://www.shichamaishou.com/doSucc",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAioZDQAbbpTtXGkR9jK1dl9mpMY1nPxq0OJESDBmuhUxDo4bCpwyMRJnMSgI3CClbl2lA941yl3qj+pZe55UimLSGLdgJv7kz+O25vxKlbkwxVzeRWkIjFuLqSUyLmVHLMuWF9tu71ZG4eoP1P4UTf9ZWLnbSwbO/W1YBHcO4mnCJbn03XjAQByOViiySi4qqPKb49EILNjgBPwkKzda1LGE0A4m+Eoq3U8wLh0EQnkUZu1vEekDVKJXVFg87sxdMa9nzglHGmjmLCWf7geur0IxWOpJqfO9GJelorjUbcZ+7kLVO8hi7KS+Hba9InlR9XtJr5UTxVbClk2Jp5VViiQIDAQAB",
		
	
);
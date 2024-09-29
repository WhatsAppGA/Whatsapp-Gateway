<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr' }}">
<head>
	<title>{{config('config.site_name')}}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="color-scheme" content="light">
	<meta name="supported-color-schemes" content="light">
	<style>
	/* Media Queries */
	@media only screen and (max-width: 600px) {
		.responsive-inner-body {
			width: 100% !important;
		}

		.responsive-footer {
			width: 100% !important;
		}
	}

	@media only screen and (max-width: 500px) {
		.responsive-button {
			width: 100% !important;
		}
	}

	/* General Styles */
	.body-style {
		box-sizing: border-box;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
		position: relative;
		-webkit-text-size-adjust: none;
		background-color: #ffffff;
		color: #718096;
		height: 100%;
		line-height: 1.4;
		margin: 0;
		padding: 0;
		width: 100% !important;
	}

	.wrapper-style {
		box-sizing: border-box;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
		position: relative;
		-premailer-cellpadding: 0;
		-premailer-cellspacing: 0;
		-premailer-width: 100%;
		background-color: #edf2f7;
		margin: 0;
		padding: 0;
		width: 100%;
	}

	.content-style {
		box-sizing: border-box;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
		position: relative;
		-premailer-cellpadding: 0;
		-premailer-cellspacing: 0;
		-premailer-width: 100%;
		margin: 0;
		padding: 0;
		width: 100%;
	}

	.header-style {
		padding: 25px 0;
		text-align: center;
	}

	.header-link-style {
		color: #3d4852;
		font-size: 19px;
		font-weight: bold;
		text-decoration: none;
		display: inline-block;
	}

	.body-td-style {
		box-sizing: border-box;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
		position: relative;
		-premailer-cellpadding: 0;
		-premailer-cellspacing: 0;
		-premailer-width: 100%;
		background-color: #edf2f7;
		border-bottom: 1px solid #edf2f7;
		border-top: 1px solid #edf2f7;
		margin: 0;
		padding: 0;
		width: 100%;
		border: hidden !important;
	}

	.inner-body-style {
		box-sizing: border-box;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
		position: relative;
		-premailer-cellpadding: 0;
		-premailer-cellspacing: 0;
		-premailer-width: 570px;
		background-color: #ffffff;
		border-color: #e8e5ef;
		border-radius: 2px;
		border-width: 1px;
		box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015);
		margin: 0 auto;
		padding: 0;
		width: 570px;
	}

	.content-cell-style {
		box-sizing: border-box;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
		position: relative;
		max-width: 100vw;
		padding: 32px;
	}

	.h1-style {
		color: #3d4852;
		font-size: 18px;
		font-weight: bold;
		margin-top: 0;
		text-align: {{ in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'right' : 'left' }};
	}

	.p-style {
		font-size: 16px;
		line-height: 1.5em;
		margin-top: 0;
		text-align: {{ in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'right' : 'left' }};
	}

	.action-style {
		box-sizing: border-box;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
		position: relative;
		-premailer-cellpadding: 0;
		-premailer-cellspacing: 0;
		-premailer-width: 100%;
		margin: 30px auto;
		padding: 0;
		text-align: center;
		width: 100%;
	}

	.button-primary-style {
		-webkit-text-size-adjust: none;
		border-radius: 4px;
		color: #fff;
		display: inline-block;
		overflow: hidden;
		text-decoration: none;
		background-color: #2d3748;
		border-bottom: 8px solid #2d3748;
		border-left: 18px solid #2d3748;
		border-right: 18px solid #2d3748;
		border-top: 8px solid #2d3748;
	}

	.subcopy-style {
		box-sizing: border-box;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
		position: relative;
		border-top: 1px solid #e8e5ef;
		margin-top: 25px;
		padding-top: 25px;
	}

	.subcopy-p-style {
		line-height: 1.5em;
		margin-top: 0;
		text-align: {{ in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'right' : 'left' }};
		font-size: 14px;
	}

	.break-all-style {
		word-break: break-all;
	}

	.reset-link-style {
		color: #3869d4;
	}

	.footer-style {
		box-sizing: border-box;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
		position: relative;
		-premailer-cellpadding: 0;
		-premailer-cellspacing: 0;
		-premailer-width: 570px;
		margin: 0 auto;
		padding: 0;
		text-align: center;
		width: 570px;
	}

	.footer-content-cell-style {
		max-width: 100vw;
		padding: 32px;
	}

	.footer-p-style {
		line-height: 1.5em;
		margin-top: 0;
		color: #b0adc5;
		font-size: 12px;
		text-align: center;
	}
</style>
</head>
<body class="body-style">
	<table class="wrapper wrapper-style" width="100%" cellpadding="0" cellspacing="0" role="presentation">
		<tr>
			<td align="center">
				<table class="content content-style" width="100%" cellpadding="0" cellspacing="0" role="presentation">
					<tr>
						<td class="header header-style">
							<a class="header-link-style" href="{{env('APP_URL')}}">
								{{config('config.site_name')}}
							</a>
						</td>
					</tr>
					<tr>
						<td class="body body-td-style" width="100%" cellpadding="0" cellspacing="0">
							<table class="inner-body inner-body-style" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
								<tr>
									<td class="content-cell content-cell-style">
										<h1 class="h1-style">{{__('Hello!')}}</h1>
										<p class="p-style">{{__('You are receiving this email because we received a password reset request for your account.')}}</p>
										<table class="action action-style" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
											<tr>
												<td align="center">
													<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
														<tr>
															<td align="center">
																<table border="0" cellpadding="0" cellspacing="0" role="presentation">
																	<tr>
																		<td>
																			<a href="{{ $url }}" class="button button-primary button-primary-style" target="_blank" rel="noopener">{{__('Reset Password')}}</a>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>{{__('Regards,')}}<br>
											{{config('config.site_name')}}
										<table class="subcopy subcopy-style" width="100%" cellpadding="0" cellspacing="0" role="presentation">
											<tr>
												<td>
													<p class="subcopy-p-style">{{__('If you are having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:')}} <span class="break-all break-all-style"><a class="reset-link-style" href="{{ $url }}">{{ $url }}</a></span>
													</p>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<table class="footer footer-style" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
								<tr>
									<td class="content-cell footer-content-cell-style" align="center">
										<p class="footer-p-style">{{ __('© :year :site_name. All rights reserved.', ['year' => date('Y'), 'site_name' => config('config.site_name')]) }}</p>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>
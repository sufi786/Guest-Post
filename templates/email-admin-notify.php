<?php
/**
 * Admin notify email template.
 *
 * This template can be overridden by copying it to yourtheme/guest-post/email-admin-notify.php.
 *
 * @package Guest_Post\Templates
 * @version 1.0.1
 */

defined( 'ABSPATH' ) || exit;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
		<title><?php echo esc_html( get_bloginfo( 'name', 'display' ) ); ?></title>
	</head>
	<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>" style="background-color: #FFF;margin: 0;padding: 70px 0;text-align: center;-webkit-text-size-adjust: none !important;width: 100%;">
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
				<tr>
					<td align="center" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1) !important;background-color: #FFF;border: 1px solid #ab9d9d;border-radius: 3px !important;">
							<tr>
								<td align="center" valign="top">
									<!-- Header -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" style="background-color: blue;border-radius: 3px 3px 0 0 !important;
	color: #FFF;border-bottom: 0;font-weight: bold;line-height: 100%;vertical-align: middle;text-align: center;">
										<tr>
											<td id="header_wrapper">
												<h1><?php echo $subject; // phpcs:ignore ?></h1>
											</td>
										</tr>
									</table>
									<!-- End Header -->
								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
									<!-- Body -->
									<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
										<tr>
											<td valign="top" id="body_content">
												<!-- Content -->
												<table border="0" cellpadding="20" cellspacing="0" width="100%">
													<tr>
														<td valign="top">
															<div id="body_content_inner">
																<p><?php echo $body; // phpcs:ignore ?></p>
															</div>
														</td>
													</tr>
												</table>
												<!-- End Content -->
											</td>
										</tr>
									</table>
									<!-- End Body -->
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>

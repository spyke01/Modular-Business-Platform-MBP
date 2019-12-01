<?php
/***************************************************************************
 *                               tours.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


//==================================================
// Dashboard tour array
//==================================================
function tours_dashboard() {
	$tourDetails = apply_filters( 'tour_details_array_dashboard',
		[
			'success' => true,
			'result'  => [
				[
					'selector'  => ".navbar ul.nav",
					'title'     => "Floating Navigation",
					'content'   => "The top navigation follows you as you scroll through the site. Click <strong>Configure</strong> to change the settings for the system.",
					//'width' => "400px",
					'placement' => "bottom",
				],
				[
					'selector'  => "#header-functions",
					'title'     => "Avatar Quick Options",
					'content'   => "This is your avatar, it provides a quick menu to change your personal options. To change this image please go <a href=\"https =>//en.gravatar.com/\">here</a> and login using your email address.",
					//'width' => "400px",
					'placement' => "bottom",
				],
				[
					'selector'  => ".mbp-sidebar ul.nav",
					'title'     => "Sidebar",
					'content'   => "The sidebar is separated into two sections, user items and admin items. <br /><br /><strong>User Menu</strong> items are your items.<br /><br /><strong>Admin Menu</strong> items allow you to see or configure items for everyone.",
					//'width' => "400px",
					'placement' => "right",
				],
				[
					'selector'  => "ul.breadcrumb",
					'title'     => "Breadcrumbs",
					'content'   => "Use this to quickly jump up a level.",
					//'width' => "400px",
					'placement' => "bottom",
				],
				[
					'selector'  => "#updateNotification",
					'title'     => "Update Notifications",
					'content'   => "This box lets you know when you need to update. We suggest performing an update whenever one is available.",
					//'width' => "400px",
					'placement' => "bottom",
				],
				[
					'selector'  => "#dashboard",
					'title'     => "Dashboard",
					'content'   => "The dashboard offers a quick look at important information. This area can be customized and expanded by using modules.",
					//'width' => "400px",
					'placement' => "top",
				],
			],
		] );

	return $tourDetails;
}
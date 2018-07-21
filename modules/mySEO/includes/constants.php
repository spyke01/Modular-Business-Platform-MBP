<?php
/***************************************************************************
 *                               constants.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/



//============================
// Set the City, State, & Zip Text
//============================
if ( ! defined( 'TXT_CITY' ) ) {
	if ( $mbp_config['ftsmbp_mySEO_citystateziptext_type'] == 1 ) {
		// For England
		define( 'TXT_CITY', 'Town' );
		define( 'TXT_STATE', 'County' );
		define( 'TXT_ZIP', 'Zipcode' );
	} else {
		// For the US
		define( 'TXT_CITY', 'City' );
		define( 'TXT_STATE', 'State' );
		define( 'TXT_ZIP', 'Zip Code' );
	}
}

//============================
// Status codes
//============================	
$WEBSITE_STATUS       = array(
	'1'  => 'Requested',
	'2'  => 'In Progress',
	'3'  => 'Active',
	'4'  => 'Launching',
	'5'  => 'Awaiting DNS Transfer',
	'6'  => 'Transferring Data',
	'7'  => 'Review',
	'8'  => 'Launched',
	'9'  => 'Removal',
	'10' => 'Removed',
);
$WEBSITE_STATUS_COLOR = array(
	'1'  => 'label-default',
	'2'  => 'label-info',
	'3'  => 'label-primary',
	'4'  => 'label-success',
	'5'  => 'label-info',
	'6'  => 'label-info',
	'7'  => 'label-info',
	'8'  => 'label-primary',
	'9'  => 'label-warning',
	'10' => 'label-danger',
);

$TASK_STATUS       = array(
	'1' => 'Open',
	'2' => 'Completed',
	'3' => 'Skipped',
	'4' => 'Rejected',
);
$TASK_STATUS_COLOR = array(
	'1' => 'label-default',
	'2' => 'label-success',
	'3' => 'label-warning',
	'4' => 'label-danger',
);
$TASK_REPORT_TYPES = array(
	'0' => 'Past 30 Days',
	'1' => 'Single Day',
	'2' => 'All Time',
);

//============================
// SEO Task Categories
//============================	
$mySEOCats = array(
	'86'  => array(
		'name'        => 'Optimize Your Presence',
		'parentID'    => '-1',
		'description' => '<p>Placeholder text for Optimize Your Presence</p>',
		'weight'      => '0',
	),
	'87'  => array(
		'name'        => 'Establish Your Presence',
		'parentID'    => '86',
		'description' => '<p>Welcome to <strong>Establish Your Presence</strong>, the first section of courses within the <strong>Optimize Your Presence</strong> curriculum.</p>

<p>To start your journey to online marketing success, we will focus you on optimizing your presence by establishing and elevating your website, social, mobile and, if applicable, local visibility. Just as a new business would open up a store or get real estate in the offline world, it is just as important for a business to "open up" or take ownership of their online real estate. </p>

<p>The most important real estate you as a business can possess online is a website that you can control and optimize. The website serves as the center of your internet universe and is a core building block of your online success. Google uses your website to determine your overall relevance. The more relevant your website the more traffic you will get from Google and other sites. We will help you claim and optimize this important piece of real estate and improve your relevancy one step at a time. </p>

<p>Once you have established your website as the key building block, we will then take you through how to claim additional real estate on social sites like Facebook and Twitter and other established websites and how to connect these sites to your website. If you are a local business, there are a large number of local sites like Google+ Local and Yelp! where you can also claim and optimize these very important pieces of online real estate and connect them to your website. </p>

<p>In the <strong>Establish Your Presence</strong> section, we will begin this real estate claiming process and start you on the path to online success. Good Luck!</p>',
		'weight'      => '0',
	),
	'88'  => array(
		'name'        => 'Website 101: The Basics',
		'parentID'    => '87',
		'description' => '<p>In <strong>Website 101: The Basics</strong> you will be completing very important tasks around claiming your website as the key building block of your online real estate. In this course, we want to make sure that you own your own domain or website address. If you already have a website, it is important to make sure Google knows about your site and that your website is setup correctly to be listed in search engines. </p>',
		'weight'      => '0',
	),
	'89'  => array(
		'name'        => 'Social 101: The Big Four',
		'parentID'    => '87',
		'description' => '<p>In <strong>Social 101: The Big Four</strong> you will be claiming your real estate on the four most important social media sites. Google+, Facebook, Twitter and LinkedIn are important pieces of your online real estate portfolio. In addition, these properties provide the foundation for present day business networking and are extremely effective tools to connect, listen and share with colleagues, clients and prospective clients. In this course, we will focus on getting these properties set up and connected to your website to start and will take you through ways to get the most out of this real estate in future courses.</p>',
		'weight'      => '1',
	),
	'90'  => array(
		'name'        => 'Local 101: Google+ Local',
		'parentID'    => '87',
		'description' => '<p>In <strong>Local 101: Google+ Local</strong> you will be claiming and optimizing your listing on Google+ Local. If your website is the center of the overall universe for online visibility, Google+ Local is the center of the local universe. In addition to your website, Google prominently shows your Google+ Local listing for important local terms that people search for on both desktop search as well as mobile search.</p>',
		'weight'      => '2',
	),
	'91'  => array(
		'name'        => 'Expand Your Presence',
		'parentID'    => '86',
		'description' => '<p>Welcome to <strong>Expand Your Presence</strong>, the second section of courses within the <strong>Optimize Your Presence</strong> curriculum.</p>

<p>Now that you have established a solid base in the Establish Your Presence section, <strong>Expand Your Presence</strong> is focused on building up your online real estate portfolio by claiming more real estate on additional social sites, local sites and top web directories and linking them back to your website. By listing your business on each of these properties, Google and search engines will see there are more online references or "votes" to your business website which in turn improves your relevancy in the eyes of Google. Improving this relevance is what leads to high search engine rankings and lots of free qualified traffic.</p>',
		'weight'      => '1',
	),
	'92'  => array(
		'name'        => 'Website 102: Top Web Directories',
		'parentID'    => '91',
		'description' => '<p>In <strong>Website 102: Top Web Directories</strong> you will be submitting your website to a number of free web directories. A directory is a website that specializes in organizing various website listings and providing links back to those websites. Listing your website on these directories will create a link from those sites to your website. Each of these links count as a vote for your site\'s relevance. Some  votes from extremely relevant websites like CNN.com carry more weight than others. In future courses, we will provide more tactics to improve both the quantity and quality of the sites that link or "vote" for your website and help improve your relevancy. </p>',
		'weight'      => '0',
	),
	'93'  => array(
		'name'        => 'Social 102: Top Social Sites',
		'parentID'    => '91',
		'description' => '<p>In <strong>Social 102: Top Social Sites</strong> you will continue to submit and claim listings on various sites. In this course, these sites are social websites beyond the Big Four which are focused around easily sharing and promoting information (ex. news articles) and content like videos and images. Again, claiming this real estate and connecting it to your website continues to improve your online real estate portfolio and overall relevancy. In future courses, we will provide you with tactics on how to leverage these sites to share content about your business and industry and why that is important. </p>',
		'weight'      => '1',
	),
	'94'  => array(
		'name'        => 'Local 102: Top Local Directories',
		'parentID'    => '91',
		'description' => '<p>In <strong>Local 102: Top Local Directories</strong> you will submit your website and optimize your listings within local directories. Having an accurate and consistent representation of your business\' name, address and phone number is extremely important. While these directories can generate traffic to your site, they also serve as references or "citations" for your Google+ Local listing. If Google sees that you are listed differently (i.e. different address or phone number) across these top local directories and others, it decreases the relevancy of your business in Google\'s eyes and they will not prominently list you in the search results.</p>',
		'weight'      => '2',
	),
	'95'  => array(
		'name'        => 'Expand Your Presence II',
		'parentID'    => '86',
		'description' => '<p>Welcome to <strong>Expand Your Presence II</strong>, the third section of courses within the <strong>Optimize Your Presence</strong> curriculum.</p>

<p>In <strong>Expand Your Presence II</strong> you will be doing 200 level tasks and, as implied, these get a bit more complex. In this section you will be optimizing your website for search engines in Website 201 and will be creating a mobile version of your site in Mobile 201. Now that you have a decent amount of online real estate linking back or connected to your website it is important to make sure your website is following some key best practices. If you are a local business, you will also continue to claim and optimize listings across a number of local directories or "citations". </p>',
		'weight'      => '2',
	),
	'96'  => array(
		'name'        => 'Website 201: Website Optimization',
		'parentID'    => '95',
		'description' => '<p>In <strong>Website 201: Site Optimization</strong> you will be doing a number of tasks to help optimize and measure the performance of your website. Many of the tasks within this course are focused on some key best practices to ensure that you are improving the relevance of your site. This course also provides some tasks to optimize your home page which is the most important and most relevant page on your site. We will provide tasks around other pages of your site in future courses.</p>',
		'weight'      => '1',
	),
	'97'  => array(
		'name'        => 'Local 201: Add\'l Local Directories',
		'parentID'    => '95',
		'description' => '<p>In <strong>Local 201: Additional Local Directories</strong> you will continue to claim and optimize your listings across local directories. As we mentioned in Local 102: Top Directories, having an accurate and consistent representation of your business\' name, address and phone number is extremely important. If Google sees that you are listed differently (i.e. different address or phone number) across these directories, it decreases the relevancy of your business in Google\'s eyes and they will not prominently list you in the search results.</p>',
		'weight'      => '3',
	),
	'98'  => array(
		'name'        => 'Adv. Presence Optimization',
		'parentID'    => '86',
		'description' => '<p>Welcome to <strong>Advanced Presence Optimization</strong>, the fourth section of courses within the <strong>Optimize Your Presence</strong> curriculum.</p>

<p>In <strong>Advanced Presence Optimization</strong> you will be doing a deeper dive into your website and building out more pages including a blog. A blog can be the most powerful tool for a small business to improve their relevancy online. While this section can be difficult these are very important tasks to complete. Please remember you can always Ask an Expert in our Help Center if you have any questions.</p>',
		'weight'      => '3',
	),
	'99'  => array(
		'name'        => 'Website 301: Adv. Site Optimization',
		'parentID'    => '98',
		'description' => '<p>In <strong>Website 301: Advanced Website Optimization</strong> you will be going back into your website and optimizing it for search engines. In addition to some more best practices, we will also make sure you have a Blog, About Us and Testimonial pages. The blog page in particular is an extremely important part of enhancing the relevancy of your website. We will be providing tactics to help you write and share blog articles in future courses.</p>',
		'weight'      => '0',
	),
	'100' => array(
		'name'        => 'Website 302: Local Links',
		'parentID'    => '98',
		'description' => '<p>In <strong>Website 302: Local Links</strong> we provide a number of ideas for you to get local links for your local business website. Getting relevant local websites that have strong "voting" power to link to your website can have a significant impact on your relevancy and how you rank for local search terms. NOTE: Unlike other courses, it is ok to Reject a number of these tasks. These are here to provide ideas and examples. If you can do 6-8 of these tasks, it will be significant.</p>',
		'weight'      => '1',
	),
	'101' => array(
		'name'        => 'Mobile 201: Go Mobile!',
		'parentID'    => '95',
		'description' => '<p>In <strong>Mobile 201: Go Mobile!</strong> we have one task for you but it is an important one. As more and more potential customers use mobile phones to search and surf the internet the more important it is for your business to have a mobile version of your website. </p>',
		'weight'      => '2',
	),
	'102' => array(
		'name'        => 'Ongoing Pres. Optimization',
		'parentID'    => '86',
		'description' => '<p>Welcome to <strong>Ongoing Presence Optimization</strong>, the fifth and final section of courses within the <strong>Optimize Your Presence</strong> curriculum.</p>

<p>First of all congratulations on getting to this final section. As implied, this section of courses provides a number of ongoing opportunities to continually optimize your presence. The optimization process is never complete as Google continues to evolve, competitors continue to compete and the world of social, local and mobile are still in early phases. </p>

<p>This section provides additional opportunities to expand your online real estate portfolio and have these additional websites link back to your website. It will be important to weigh the Points and Effort associated with these tasks with other tasks in other areas of the curriculum. </p>',
		'weight'      => '4',
	),
	'103' => array(
		'name'        => 'Website 401: Add\'l Directories II',
		'parentID'    => '102',
		'description' => '<p>In <strong>Website 401: Additional Directories</strong> we provide ongoing opportunities to submit your site to various web directories. These directories again provide a way for you to relatively easily get a link to your website from other websites. Some of these directories require payment as noted in the task. Higher quality sites typically cost more but could be considered more relevant by Google.  NOTE: It is important to diversify the ways you get websites to link to your site. Overusing general web directories (i.e. representing more than 50% of the links to your site) could be flagged by Google as being over-optimized and you could be penalized.</p>',
		'weight'      => '0',
	),
	'104' => array(
		'name'        => 'Website 402: Competitor Links',
		'parentID'    => '102',
		'description' => '<p>In <strong>Website 402: Competitor Links</strong> you will be shown opportunities to get links from websites that link back to your competitors\' websites. As discussed, getting sites to link to your site is extremely important to improve the overall relevancy of your site. Some websites have more "voting power" in the eyes of the engines and this power is indicated by the Link Strength noted in each task. Only ten open tasks at a time will be displayed and they are sorted based on the number of competitors who have that link and the link\'s strength.</p>',
		'weight'      => '1',
	),
	'148' => array(
		'name'        => 'Website 403: Content Optimization',
		'parentID'    => '102',
		'description' => '<p>In <strong>Website 403: Content Optimization</strong> you will be optimizing each pages content for maximum organic SEO.</p>',
		'weight'      => '2',
	),
	'105' => array(
		'name'        => 'Social 401: Add\'l Social Sites',
		'parentID'    => '102',
		'description' => '<p>In <strong>Social 401: Additional Social Sites</strong> you will have additional opportunities to claim real estate on social sites and link them back to your website. This can help with your online real estate portfolio and overall relevancy but again it is important to diversify your link strategy. Please weigh these opportunities against higher Point opportunities in other sections of the curriculum.</p>',
		'weight'      => '3',
	),
	'106' => array(
		'name'        => 'Manage Your Reputation',
		'parentID'    => '-1',
		'description' => '<p>Welcome to <strong>Manage Your Reputation Basics</strong>, the first section of the <strong>Manage Your Reputation</strong> curriculum.</p>

<p>Now that you have started to build out and are optimizing your real estate in the Optimize Your Presence section, you are now ready to start leveraging some of your online real estate as listening tools to understand what your customers are saying about you. </p>

<p>As the internet has gotten more social over the years, consumers are now much more emboldened to share their experiences with businesses. They now have a megaphone and can either use it to promote or slam your business. Within the past few years search engines are starting to take this social sentiment into consideration when it comes to relevancy and ranking your business. For example, Google will be hesitant to give you a #1 ranking if your Google+ Local page has a large number of negative reviews. They can also provide a bigger megaphone to an individual by ranking their review of your business highly in the search results. Small businesses need to now actively manage their reputation.</p>

<p>As it relates to your reputation, there are three groups of people. The people who love you and your business and tell others about their great experience to others. These people are known as your promoters. Then there are people who had a negative experience and are telling others about their bad experience and not to work with you. These people are known as your detractors. The third group is the majority of people who fall in between the promoters and detractors. There experience with you was neither horrible nor fantastic and are generally neutral to your brand.</p>

<p>The key to managing your reputation online is to encourage your promoters to tell their story via reviews, testimonials, endorsements, etc and help them get the word out on your business. The other key is make sure and monitor for the detractors and to quickly respond to those detractors open and honestly. </p>

<p>We\'ll provide a way to do both of this in this section.  Good luck!</p>',
		'weight'      => '1',
	),
	'107' => array(
		'name'        => 'Reputation Management Basics',
		'parentID'    => '106',
		'description' => '<p>Welcome to <strong>Manage Your Reputation Basics</strong>, the first section of the <strong>Manage Your Reputation</strong> curriculum.</p>

<p>Now that you have started to build out and are optimizing your real estate in the Optimize Your Presence section, you are now ready to start leveraging some of your online real estate as listening tools to understand what your customers are saying about you. </p>

<p>As the internet has gotten more social over the years, consumers are now much more emboldened to share their experiences with businesses. They now have a megaphone and can either use it to promote or slam your business. Within the past few years search engines are starting to take this social sentiment into consideration when it comes to relevancy and ranking your business in search engines. For example, Google will be hesitant to give you a #1 ranking if your Google+ Local page has a large number of negative reviews. They can also provide a bigger megaphone to an individual by ranking their review of your business highly in the search results. Small businesses now need to actively manage their reputation.</p>

<p>As it relates to your reputation, there are three groups of people. The people who love you and your business and tell others about their great experience. These people are known as your promoters. Then there are people who had a negative experience and are telling others about their bad experience and not to work with you. These people are known as your detractors. The third group is the majority of people who fall in between the promoters and detractors. Their experience with you was neither horrible nor fantastic and are generally neutral to your brand.</p>

<p>The key to managing your reputation online is to encourage your promoters to tell their story via online reviews, testimonials, endorsements, etc and help them get the word out on your business. The other key is make sure and monitor the detractors and to quickly respond to those detractors openly and honestly. </p>

<p>We\'ll provide a way to do both in <strong>Manage Your Reputation</strong>.  Good luck!</p>',
		'weight'      => '0',
	),
	'108' => array(
		'name'        => 'Reviews 101: Generate Reviews',
		'parentID'    => '107',
		'description' => '<p>In <strong>Reviews 101: Generate Reviews</strong> you will receive tasks focused around getting your promoters or brand advocates to provide you via online reviews. A review on a third party site like Google+ Local is the most powerful mechanism for an existing customer to promote your business. There are a number of review sites available to users but the three most important sites that we focus you on are Google+ Local, Yahoo! Local and CitySearch.  </p>',
		'weight'      => '0',
	),
	'109' => array(
		'name'        => 'Word-of-Mouth 101: Endorsements',
		'parentID'    => '107',
		'description' => '<p>In <strong>Word-of-Mouth 101: Endorsements</strong> we provide a number of tactics that allow your promoters to endorse your business via social media tools. Sometimes getting people to provide full reviews or testimonials of your business can be difficult. It is still helpful to your online relevancy to have them +1 your home page or do a number of quick things that indicate an endorsement from a customer.</p>',
		'weight'      => '1',
	),
	'110' => array(
		'name'        => 'Ongoing Rep Management',
		'parentID'    => '106',
		'description' => '<p>Welcome to <strong>Ongoing Reputation Management</strong> the second of two sections within <strong>Reputation Management</strong>.</p>

<p>In Ongoing Reputation, we will create tasks for reviews for your local businesses, if applicable, as well as tasks for mentions of your business within social media. The reviews are pulled in from over a dozen review sites and social sites like Facbeook and Twitter and brought in as tasks within the system for you to respond accordingly. </p>

<p>This section is not currently available but is <strong>COMING SOON</strong> so stay tuned.  </p>',
		'weight'      => '1',
	),
	'111' => array(
		'name'        => 'Reviews 301: Review Monitoring',
		'parentID'    => '110',
		'description' => '<p>In <strong>Reviews 301: Review Monitoring</strong> you will be able track and monitor reviews for each of your locations as they come in daily from the top review sites on the internet including Google+ Local, Yahoo! Local, CitySearch, Yelp and over a dozen others.</p>

<p>This course is currently unavailable but will be <strong>COMING SOON</strong>.</p>',
		'weight'      => '0',
	),
	'112' => array(
		'name'        => 'Social Listening 301: Brand Monitoring',
		'parentID'    => '110',
		'description' => '<p>In <strong>Social Listening 301: Brand Monitoring</strong> you will receive tasks list mentions of your business on social media properties including Facebook and Twitter and queue them up for responses.</p>

<p>This course is currently unavailable but will be <strong>COMING SOON</strong>.</p>',
		'weight'      => '1',
	),
	'113' => array(
		'name'        => 'Become an Authority',
		'parentID'    => '-1',
		'description' => '<p>Placeholder text for Become an Authority</p>',
		'weight'      => '2',
	),
	'114' => array(
		'name'        => 'Become an Authority Basics',
		'parentID'    => '113',
		'description' => '<p>Welcome to <strong>Become An Authority Basics</strong>, the first section of the <strong>Become An Authority</strong> curriculum.</p>

<p>Now that you have been optimizing your online real estate and using this real estate to listen to what people are saying about your business, it is now time to leverage this real estate to promote yourself as an authority and in turn promote your business. </p>

<p>Become an Authority involves a number of strategies and tactics around engaging and networking within online communities and providing advice and insights on your area of expertise.  At the core of Become an Authority is the relatively new area of content marketing. </p>

<p>Content marketing is a strategy in which businesses  create and share information in order to engage potential customers to ultimately drive leads and revenue. Consumers of various products and services are asking more of their providers and saying "tell me something I don\'t know" and are using the internet to find this information. Search engines are strongly favoring businesses that can provide and promote the information their customers are looking for via their website, social media and other online properties. In addition to search engine visibility, businesses with great information/content get shared across social media such as Twitter creating another large source of free traffic. </p>

<p>We know sharing and creating content is not easy! We\'ll provide you a step-by-step way to become a great content marketer. In <strong>Become An Authority Basics</strong> we\'ll start off by having you engage in a number of online knowledge sharing networks and start to get you started on the most important of all content marketing strategies - blogging!</p>',
		'weight'      => '0',
	),
	'115' => array(
		'name'        => 'Social Networking 101: Join the Conversation',
		'parentID'    => '114',
		'description' => '<p>In <strong>Social Networking 101: Join the Conversation</strong> you will learn some basic strategies to start networking with other thought leaders in your industry. Tools like Twitter can be overwhelming to start. The key is to see how people are using Twitter, LinkedIn and online question and answer communities. Learn from those that are already authorities in your industry and when comfortable start to engage in the conversation. </p>',
		'weight'      => '0',
	),
	'116' => array(
		'name'        => 'Blog 101: Basic Blog Writing',
		'parentID'    => '114',
		'description' => '<p>In <strong>Blog 101: Basic Blog Writing</strong> you can follow the tasks in this section for initial blog ideas. Blogging may be the most effective strategy to improve the relevancy of your website, get search engines to notice your site and get more customers. It is also important to share any blog posts across the social media sites you created in the Optimize Your Presence section. Becoming a good blog writer can take time but you need to start somewhere!</p>',
		'weight'      => '1',
	),
	'117' => array(
		'name'        => 'Image & Video 101: Image Sharing',
		'parentID'    => '114',
		'description' => '<p>In <strong>Image &amp; Video 101: Image Sharing</strong> you will be doing tasks around sharing images across the various photo and image accounts your created in the Optimize Your Presence section. In addition to blog articles, images are another form of content that can be shared and promoted across your online real estate. While images are not as informative as blog articles, they can still be of interest to searchers/potential customers, can humanize your business and allows you to share more about your company. </p>',
		'weight'      => '2',
	),
	'118' => array(
		'name'        => 'Ongoing Authority Development',
		'parentID'    => '113',
		'description' => '<p>Welcome to <strong>Ongoing Authority Development</strong> which is the second section in <strong>Become An Authority</strong>.</p>

<p>In this section of courses you will have ongoing opportunities to share and promote yourself via blogs, images and video. Again these are opportunities to share your knowledge, continue to develop a persona for your business and connect with potential customers.</p>',
		'weight'      => '1',
	),
	'119' => array(
		'name'        => 'Social Networking 301: Monitoring & Sharing',
		'parentID'    => '118',
		'description' => '<p>In <strong>Social Networking 301: Monitoring &amp; Sharing</strong> you will have the opportunity to review and potentially share articles and information across your social media channels. This strengthens the social networking connections you have made through Twitter, etc and allows you to provide valuable information to those that are following you.</p>

<p>This course is currently unavailable but will be <strong>COMING SOON</strong>.</p>',
		'weight'      => '0',
	),
	'120' => array(
		'name'        => 'Blog 301: Advanced Blog Writing',
		'parentID'    => '118',
		'description' => '<p>In <strong>Blog 301: Advanced Blog Writing</strong> you will have ongoing blog writing opportunities. It is important to set a goal for yourself to put out a blog article once a month, once a week or once a day. Whatever that goal is, be sure to hit it and keep the new information coming!</p>',
		'weight'      => '1',
	),
	'121' => array(
		'name'        => 'Image & Video 301: Video Sharing',
		'parentID'    => '118',
		'description' => '<p>In <strong>Image &amp; Video 301: Video Sharing</strong> you will get tasks around creating and sharing video across the social video accounts like YouTube you created in Optimize Your Presence. Video is a growing medium and a phenomenal piece of content to share knowledge while telling your business\' story. We provide a number of ideas that can be inexpensive to produce and easy to promote.</p>',
		'weight'      => '2',
	),
	'122' => array(
		'name'        => 'Convert',
		'parentID'    => '-1',
		'description' => '<p>Placeholder text for Convert</p>',
		'weight'      => '3',
	),
	'123' => array(
		'name'        => 'Conversion Basics',
		'parentID'    => '122',
		'description' => '<p>Welcome to <strong>Conversion Basics</strong>, the first and only section for the <strong>Convert</strong> curriculum.</p>

<p>So now that you have dramatically increased your online visibility through all of the courses above it is important to start to focus in on the traffic your marketing activities is generating and how to effectively convert that traffic into revenue. </p>

<p>In this section, we provide some key best practices to effectively convert visitors into leads. Weâ€™ll continue to build out this section over time as driving leads and nurturing them to a close is both an art and a science.</p>',
		'weight'      => '0',
	),
	'124' => array(
		'name'        => 'Measure & Convert 101: Key Items',
		'parentID'    => '123',
		'description' => '<p>In <strong>Measure and Convert 101: Key Items</strong> you will complete tasks to do some fundamental best practices to converting web traffic to leads. The key elements include contact forms and other mechanisms to capture a name, email address and/or phone number. The other tasks are around creating trust with the end customer and doing that by providing symbols of trust and providing legal documentation around warranties, etc.</p>',
		'weight'      => '0',
	),
	'125' => array(
		'name'        => 'Retain & Grow',
		'parentID'    => '-1',
		'description' => '',
		'weight'      => '4',
	),
	'126' => array(
		'name'        => 'Retention Basics',
		'parentID'    => '125',
		'description' => '<p>Welcome to the <strong>Retention Basics</strong> section of the <strong>Retain &amp; Grow</strong> curriculum.</p>

<p>Now that you have dramatically increased your online presence and are starting to convert your customers into leads, it is important to continuously stay in front of your customers for repeat business and reference opportunities discussed in the reputation management section.</p>

<p>Given all of the time and effort that you put in to acquiring a customer, it\'s extremely important to invest time in your existing customers since they are much more likely to work with you or buy your products again given the prior relationship.</p>',
		'weight'      => '0',
	),
	'127' => array(
		'name'        => 'Retain 101: Email and Social Media Strategies',
		'parentID'    => '126',
		'description' => '<p>In <strong>Retain 101: Email and Social Media</strong> you will have do various tasks leveraging email and social media tools to retain and grow your existing customer base. The most important starting point which can be easier said than done is to consolidate all of your customers email addresses in one database. Once consolidated there are a number of strategies to provide ongoing value at low cost and keep your business top of mind.</p>',
		'weight'      => '0',
	),
	'146' => array(
		'name'        => 'Website 202: Add\'l Directories I',
		'parentID'    => '95',
		'description' => '',
		'weight'      => '99',
	),
	'147' => array(
		'name'        => 'Website 303: Blog Links',
		'parentID'    => '98',
		'description' => '',
		'weight'      => '99',
	),
);

//============================
// SEO Tasks
//============================	
$mySEOTasks = array(
	'88'  => array(
		'1' => array(
			'title'       => '<p>Make sure your website is listed in Google.</p>',
			'description' => '<p>For your website to be ranked in Google, your website must first be able to be found by Google and included in its massive database of websites. </p>

<p>Although Google crawls billions of pages to populate its database, it\'s inevitable that some sites will not be found by Google. If Google misses a site, it\'s frequently for one of the following reasons:</p>

<ul>
<li>There are not many other websites that link to your website (more on this subject later).</li>
<li>Your site launched after Google\'s last attempt of crawling and databasing the websites on the Internet.</li>
<li>Your website design made it difficult for Google to effectively crawl and database the content. (more on this subject later).</li>
<li>Your site was temporarily unavailable when Google tried to crawl it, or Google received an error when they tried to crawl it.</li>
</ul>',
			'howTo'       => '<p>Go to Google and do a search for "site:mySite.com" where mySite.com is the client\'s website URL.</p>',
			'effort'      => '15',
			'impact'      => '25',
			'weight'      => '2',
		),
		'2' => array(
			'title'       => '<p>Make sure your website is listed in Yahoo! And Bing.</p>',
			'description' => '<p>Yahoo! and Bing share the same search results and are both powered by Microsoft\'s Bing search engine. For your website to be ranked in Yahoo! and Bing your website must first be able to be found by the Bing web crawler and included in their  database of websites. </p>

<p>Although Bing crawls billions of pages to populate its database, it\'s inevitable that some sites will be missed. If Bing misses a site, it\'s frequently for one of the following reasons:</p>

<ul>
<li>There are not many other websites that link to your website (more on this later).</li>
<li>Your site launched after Bing\'s most recent crawl was completed.</li>
<li>The design of your site makes it difficult for Bing to effectively crawl and databse your content (more on this later as well).</li>
<li>Your site was temporarily unavailable when Bing tried to crawl it or Bing received an error when they tried to crawl it.</li>
</ul>',
			'howTo'       => '<p>Go to Bing and Yahoo (should be the same results) and do a search for "site:mySite.com" where mySite.com is the client\'s website URL.</p>',
			'effort'      => '15',
			'impact'      => '15',
			'weight'      => '3',
		),
		'3' => array(
			'title'       => '<p>Confirm that your website\'s robots.txt settings allow search engines to crawl and database your website.</p>',
			'description' => '<p>&quot;Robots.txt&quot; is a text file that tells search engines whether they can access and thus crawl parts of your website. If this text file is set up incorrectly, it may unintentionally keep your website from getting included in search engines.</p>',
			'howTo'       => '<p>If you edit your site\'s HTML directly, you will most likely find robots.txt in the default directory. Do not worry if it does not exist, as it is not a mandatory file. You can address this task as needed per the <a href="http://www.google.com/support/webmasters/?hl=en">Google Webmaster Help Center</a>. </p>

<p>If you use a content management system (CMS) to manage your site, refer to your individual CMS documentation about how to manage robots.txt through the CMS. </p>

<p>Note that not all CMS providers directly control the robots.txt file. </p>',
			'effort'      => '30',
			'impact'      => '50',
			'weight'      => '5',
		),
		'4' => array(
			'title'       => '<p>Confirm that your website\'s meta tag settings allow search engines  to crawl and database your website.</p>',
			'description' => '<p>A meta noindex tag can be used to entirely prevent a page?s contents from being listed in search engine results even if other websites link to it. If a meta noindex tag is accidentally inserted into your website, it will prevent the page from showing up in Google\'s search results. </p>',
			'howTo'       => '<p>This is a technical task that requires you to edit via HTML directly. In order to remove this tag from the homepage, search for the following text on your homepage source code:</p>

<p>&lt;meta name=&quot;robots&quot; content=&quot;noindex&quot;&gt; </p>

<p>If you are certain that you do not want the page included in search engines, then leave as is. If you do want the page to be included, then delete it and save your changes.</p>',
			'effort'      => '15',
			'impact'      => '50',
			'weight'      => '6',
		),
	),
	'89'  => array(
		'5' => array(
			'title'       => '<p>Create a <a href="https://plus.google.com">Google+</a> Business Page.</p>',
			'description' => '<p>Businesses and organizations are able to create pages in the <a href="https://plus.google.com">Google+</a> network. This is a very important way to increase your presence online and improve your overall visibility.</p>

<ul>
<li>Much like other popular social networks like Twitter and Facebook, creating a <a href="https://plus.google.com/pages/create">Google+ page </a> for your business enables you to connect and share with colleagues, customers and prospective customers.</li>
<li>While Google+ does not have the traction that Facebook and Twitter have from a number of users standpoint, Google is relying strongly on Google+ information to help determine which businesses are relevant and can significantly influence search results for your website. In other words, if Google thinks it is important, so should you!</li>
<li>Google+ information shows up in search results on Google.com. This can include recent posts, local information, photos, videos, and much more. By using Google+ you can get found across Google, right when your customers are most interested.</li>
<li><a href="http://support.google.com/plus/?hl=en">Click here</a> to learn more about the basics of Google+.</li>
</ul>',
			'howTo'       => '<p>Follow these steps to create a robust Google+ listing:</p>

<ul>
<li>Go to the Create a <a href="https://plus.google.com/pages/create">Google+ Page link</a> to get started.</li>
<li>As a non-local business (ex. ecommerce company), you should select Company, Institution, or Organization.</li>
<li>Be sure provide a link to your website.</li>
<li>Enter in your tagline and upload a small logo or photo for your business. You may have to adjust the size of the logo to meet Google\'s size requirements.</li>
<li>Be sure to include as much content as you can on the business page. We\'d recommend starting to add the basic information about your business, and then go back to enter in more details.</li>
<li>In addition, the information (address, phone, keywords, recommended links, categories, etc.) that you post should be exactly the same as what is shown on your website.</li>
</ul>',
			'effort'      => '30',
			'impact'      => '100',
			'weight'      => '1',
		),
		'6' => array(
			'title'       => '<p>Create a <a href="http://www.twitter.com">Twitter</a> account.</p>',
			'description' => '<p><a href="http://www.twitter.com">Twitter</a> is a free social networking service. Twitter allows people to connect with each other and write messages using a limit of 140 characters. Twitter can be a powerful tool that enables the following strategies: </p>

<ul>
<li>Follow industry and thought leaders in your respective industry and start to take part of the online conversations regarding your industry.</li>
<li>Find out if your customers are talking about you or your business and be able to respond accordingly.</li>
<li>Use Twitter as way to promote your business and yourself as an authority or thought leader in your industry.</li>
<li>Creates another instance of your business that can show up search engine results.</li>
<li>You will learn how to do all of these tactics in later sections of the curriculum. Start by creating your account today!</li>
</ul>',
			'howTo'       => '<p>Click <a href="http://www.twitter.com">here</a> to sign up for a Twitter account. You can sign up either as yourself (ex. Bob Smith), your company (Smith Plumbing) or both. We generally recommend that you sign up as your business name.</p>

<p>Some additional best practices to consider when setting up your Twitter account are listed below:</p>

<ul>
<li>Make sure the full name of your Twitter account is the official name of your business.</li>
<li>Include of couple of your targeted keywords in your profile description.</li>
<li>Add your location to your Twitter profile.</li>
<li>Customize the your account background using some of the images related to your business.</li>
<li>IMPORTANT: Make sure to provide a link to your website in your profile area.</li>
</ul>',
			'effort'      => '15',
			'impact'      => '25',
			'weight'      => '2',
		),
		'7' => array(
			'title'       => '<p>Create a <a href="http://www.facebook.com">Facebook </a> Business Page.</p>',
			'description' => '<p><a href="http://www.facebook.com">Facebook</a> is the leading social networking site, with more than one billion users. Facebook is one of the best ways for businesses to reach out to current friends and potential customers. </p>

<ul>
<li>Facebook pages are aimed for businesses to showcase information and communicate with users.</li>
<li>A Facebook fan page will often rank in the top results for brand-related searches.</li>
<li>Facebook fan pages allow better interaction with your brand and with fans. Many companies with Facebook pages have seen the positive benefit of viral marketing through their fan page.</li>
<li>The process of posting content on a page, such as events, news updates, promotions videos, widgets, fan comments, and surveys is quick, free, easy, and painless.</li>
</ul>',
			'howTo'       => '<p>Click <a href="https://www.facebook.com">here</a> to start a <a href="http://www.facebook.com/pages/create.php?campaign_id=372931622610&amp;placement=pghm&amp;extra_1=0">Facebook Page.</a>  </p>

<p>If you are a Local Business select Local Business or Place on the Create a Page area. If you are not Local (ex. ecommerce company), you should select Company, Organization or Institution. </p>

<p>IMPORTANT: When you create a business page be sure to do the following:</p>

<ul>
<li>Make sure the title of your page is the official name of your business.</li>
<li>Provide a link to your website.</li>
<li>If your business is local, be sure to enter your address and phone number exactly the same way you entered it into our system.</li>
</ul>',
			'effort'      => '15',
			'impact'      => '25',
			'weight'      => '3',
		),
		'8' => array(
			'title'       => '<p>Create an individual <a href="http://www.linkedin.com">LinkedIn</a> Profile.</p>',
			'description' => '<p><a href="http://www.linkedin.com">LinkedIn</a> is a highly regarded social network for professionals to share information, links, and more, with millions of users and companies. </p>

<p>Your personal LinkedIn profile creates another opportunity to increase your overall presence online. For B-to-B companies, it also provides a present day method of networking and allows you to stay connected with colleagues, customers and prospective customers. </p>',
			'howTo'       => '<p><a href="http://www.linkedin.com">Click here</a> to create your LinkedIn personal account. Be sure to include your current business in your list of employers and be sure to include a link back to your website.</p>

<ul>
<li>Create a personal profile for yourself. You may also find it valuable to create a business group or page.</li>
<li>Profiles should contain a professional photo, a link to your business, keywords within the content, and information about yourself or your business.</li>
</ul>',
			'effort'      => '15',
			'impact'      => '15',
			'weight'      => '4',
		),
		'9' => array(
			'title'       => '<p>Create a <a href="http://www.linkedin.com">LinkedIn</a> Business Page.</p>',
			'description' => '<p>In addition to creating a personal profile, LinkedIn provides a robust way to list your business. This strategy provides another instance of your business on the internet, increasing your overall online profile and providing another tool to connect and share with colleagues, customers and prospective customers.</p>',
			'howTo'       => '<p><a href="http://www.linkedin.com/company/add/show">Click here</a> to create your LinkedIn business page account. Some best practices to follow when creating your page are as follows:</p>

<ul>
<li>Provide a short business overview.</li>
<li>Provide a link to your business website.</li>
<li>Provide a robust overview of your products and services.</li>
<li>Upload logos and other corporate images to make your business page appealing to users.</li>
</ul>',
			'effort'      => '15',
			'impact'      => '15',
			'weight'      => '5',
		),
	),
	'90'  => array(
		'10' => array(
			'title'       => '<p>Create a <a href="http://www.google.com/local">Google+ Local</a> Business Page.</p>',
			'description' => '<p>When searching for businesses online, most users search for products and services that are geographically near to them. The <a href="http://www.google.com/+/learnmore/local/">Google+ Local listings</a> are prominently listed for such local searches.  This is free opportunity to feature your business name, contact information, working hours, and more.</p>

<p>Google+ Local is a free web service for local businesses to provide a platform to connect with their customers. This potent web tool allows you to list your physical location, monitor and respond to customer reviews, and optimize for your local business. </p>

<p>Many consider Google+ Local the center of the local universe, thus creating and managing your Google+ Local listing is just as important as owning and operating your website.</p>

<ul>
<li><p>Once you verify your ownership over your business on Google+ Local, it can be searched by going to <a href="http://maps.google.com">maps.google.com</a> or by going to Google.com.</p></li>
<li><p>Google+ Local listings often appear on searches that include regional keywords, such as &quot;Iowa City restaurants&quot; but are now coming up for searches for services that are typically done on a local level (ex. tax accountant).</p></li>
</ul>',
			'howTo'       => '<ul>
<li>Click <a href="http://www.google.com/+/business/">here</a> and choose &quot;Local Business&quot; or &quot;Place&quot; to add your free business listing to Google+ Local. Type in your phone number in the following format: (555) 555-5555 and click &quot;Locate.&quot;</li>
</ul>

<p>There are two cases:</p>

<ul>
<li>Google will not find any listing. In this case you should click on &quot;Add your business to Google&quot; and follow the steps.</li>
<li>Google will find a listing for your business. In this case you should click on the listing and confirm that all the information is correct. Then you should click &quot;OK,&quot; and Google will prompt you to verify your ownership over the listing.</li>
<li>When creating the listing, it is recommended that you use a business email under your domain (for example: email@yourdomain.com).</li>
<li>Once your business listing is owner-verified, it will take up to 3 days for your profile to appear publicly online.</li>
</ul>',
			'effort'      => '30',
			'impact'      => '100',
			'weight'      => '1',
		),
		'11' => array(
			'title'       => '<p>Make sure your business name, address, and phone number are filled in correctly on your <a href="https://plus.google.com/pages/create">Google+ Local</a> page.</p>',
			'description' => '<p>Business name, address, and phone number (NAP for short) are the three most important elements on your Google+ Local page, which Google uses when identifying a business. These three elements should be consistent everywhere any information for the business appears online. </p>

<p>The business name should be the same as the one you are publicly known with (the Doing-Business-As) and not necessarily the same as the name your business is officially registered with.</p>',
			'howTo'       => '<p>Double check if the business name, address, and phone number on your <a href="https://plus.google.com/pages/create">Google+ Local</a> listing are correct and match the real world information.</p>',
			'effort'      => '15',
			'impact'      => '75',
			'weight'      => '2',
		),
		'12' => array(
			'title'       => '<p>In <a href="https://plus.google.com/pages/create">Google+ Local</a>, choose the best categories for your business.</p>',
			'description' => '<p>Categories are the most important element of the Google+ Local listing that Google uses when determining if a business is relevant to particular search. </p>

<p>For example, if one of your categories is &quot;lawyer,&quot; it is very likely that Google will display your listing when someone searches for a lawyer in your area of business. However, if your category is &quot;plumber,&quot; it is very unlikely that this will happen. You have the right to choose up to 5 categories for your Google+ Local listing, and at least one of them should be a category recommended by Google.</p>',
			'howTo'       => '<p>According to the <a href="http://support.google.com/places/bin/answer.py?hl=en&amp;answer=107528">Google Local Quality Guidelines</a> the categories for a business listing should depict what your business is, not what it does, or what products it sells. </p>

<p>For instance, a category such as &quot;hospital&quot; is acceptable, but categories such as &quot;vaccinations&quot; or &quot;printer paper&quot; are not. You can use the recommendations of <a href="http://blumenthals.com/google-lbc-categories/search.php?q=&amp;val=hl-gl%3Den-US%26ottype%3D1">this tool</a> to help you choose the best category for your business. You can also check the categories your local competitors, as well as other businesses from the same industry as yours are using, for other ideas.</p>',
			'effort'      => '15',
			'impact'      => '75',
			'weight'      => '3',
		),
		'13' => array(
			'title'       => '<p>Fill in all the information required (description, photos, videos, additional details) on your <a href="https://plus.google.com/pages/create">Google+ Local</a> listing.</p>',
			'description' => '<p>Filling in your Google+ Local listing completely is a very important step you should take because it helps in two main ways:</p>

<ul>
<li><p>This helps Google learn more about your business, which will result in additional trust assigned to your listing, which in turn would mean that Google will be more likely to display it in local search results.</p></li>
<li><p>This also helps potential customers to learn more about your business, so the chances for your customers to call you or to visit your location would increase significantly.</p></li>
</ul>',
			'howTo'       => '<p>Follow these tips:</p>

<ul>
<li><p>Add a description for your business that provides more details about who you are and what products and services you offer. However, do not stuff your description with keywords as they will only affect your listing\'s performance negatively and will not be helpful for the regular user.</p></li>
<li><p>Add your website, business email, working hours, payment methods, and other additional details for your business (such as parking availability, languages spoken, associations participation, for example).</p></li>
<li><p>Add up to 10 photos and up to 5 videos for your business. The first photo you add will show up in the search results on Google Maps and Google+, so make sure you choose this photo particularly wisely. It is best if the photo represents a sample of your product/service, yourself, or your team. Avoid adding your business logo as the first picture for your listing.</p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '25',
			'weight'      => '4',
		),
		'14' => array(
			'title'       => '<p>Make sure your business\'s map pin is at the correct location.</p>',
			'description' => '<p>The map pin is the main element of a listing Google uses when determining where your business is physically located. If your map pin is in an incorrect location, you might disappear from the local search results, or Google might display incorrect driving directions to potential clients.</p>',
			'howTo'       => '<p>If you serve customers at your business location, make sure your map pin is located exactly where it should be.</p>

<p>If you serve customers at their location and you do business from home, make sure you select &quot;Yes, this business serves customers at their locations&quot; from your dashboard and click &quot;Do not show my business address on my Maps listing.&quot; If you fail to do that, Google might de-list your business from the local search results.</p>',
			'effort'      => '15',
			'impact'      => '75',
			'weight'      => '5',
		),
		'15' => array(
			'title'       => '<p>Make sure you don\'t have duplicate listings on Google Maps.</p>',
			'description' => '<p>With its fast response times, interactive maps, and worldwide coverage, <a href="https://www.google.com/maps">Google Maps</a> is the go-to application for customers and local small businesses. However, sometimes Google creates additional business listings for the same business, and duplicate listings are common occurrences for local small businesses. This incident could negatively affect your local search rankings. </p>

<p>The main reason for this is Google receives business information from multiple sources. It tries to compile all this information together, but in some occasions it fails to do so, and that is when a duplicate listing occurs.</p>

<p>While these placements are frequent mistakes, Google may penalize sites that violate its strict rules on duplicate listings. Google will grant lower search rankings and give decreased online visibility to businesses. Google allows you the opportunity to find your duplicates and erase them to maintain your rankings. It is important for your business to recognize when it has multiple map locations. </p>',
			'howTo'       => '<p>The easiest way to discover duplicate listings for your business is by going to <a href="https://www.google.com/maps">Google Maps</a> and searching for your phone number (or each of the phone numbers you use for business), and then your business name + city of address. </p>

<p>If you do not find any listings other than the one that you created and it is owner-verified, this means you do not have duplicates on Google Maps. </p>

<p>However, if you find one or more other listings, you should follow these steps:</p>

<ul>
<li>You will need to get rid of any duplicate listings if they are found. Starting from <a href="https://www.google.com/maps">Google Maps</a>, click on your duplicate listing and hit Suggest an edit.</li>
<li>At the Report a Problem window, click on the incorrect information displayed in the listing, leave a note to be addressed, or report something else and send general feedback.</li>
<li>When hitting the Report something else button, you can highlight where on the map you see an issue (such as noting your duplicate listing).</li>
<li>After sending in your request, you will receive email updates from the Google Places Team on the issue.</li>
<li>The update you submit will not go into effect right away. If your request is not resolved within several weeks, talk to a <a href="https://support.google.com/places/contact/c2c_places">specialist</a> about removing your duplicate listing.</li>
</ul>',
			'effort'      => '15',
			'impact'      => '75',
			'weight'      => '6',
		),
		'16' => array(
			'title'       => '<p>Add special offers to your Google+ Local listing.</p>',
			'description' => '<p>Special offers and discounts are one of the easiest ways to attract customers. Google allows you to add unlimited number of offers for your Google+ Local listing. </p>

<p>The offers will appear not only on your Google+ Local listing, but also when someone searches using the Google Maps for Android, Google Offers, or Google Wallet mobile apps. Currently, Google\'s Offers functionality is free for a &quot;limited-time trial period.&quot;</p>',
			'howTo'       => '<p>To create an offer, you have to log in to your Google Places dashboard and click on the &quot;Offers&quot; tab on top. Then you should <a href="https://www.google.com/local/add/offers/?hl=en-US&amp;gl=US#create">Click here</a> and add the details for your business\'s offer. </p>

<p>You can choose the validity period of the offer, the maximum number of people that could redeem it, and add in specific terms and conditions.</p>

<p>Once your offer is ready and customers start using it, you can monitor how many people have redeemed it by clicking <a href="https://www.google.com/local/add/offers/?hl=en-US&amp;gl=US#">here</a>.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '8',
		),
	),
	'92'  => array(
		'17' => array(
			'title'       => '<p>Submit your site to <a href="http://www.dmoz.org/">DMOZ</a>.</p>',
			'description' => '<p><a href="http://www.dmoz.org/">DMOZ</a> stands for the The Open Directory Project. DMOZ is a quality free web directory with hand-picked links chosen by editors. Directories are a great source for relevant links to websites often broken down into categories. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to a website. </p>

<p>It should be known that acceptance in DMOZ is determined by the specific editor\'s liking of your content, and you will need to follow their <a href="http://www.dmoz.org/docs/en/add.html/">guidelines</a> in order for your site to be accepted. The time it takes to get your site approved can vary.</p>',
			'howTo'       => '<p>Here are some quick tips for how to submit your business to DMOZ:</p>

<ul>
<li>Read about how to <a href="http://www.dmoz.org/docs/en/help/submit.html">add a DMOZ link</a>. The directions here will help you determine if DMOZ is a good fit for your site.</li>
<li>Then, go to the <a href="http://www.dmoz.org">DMOZ homepage</a> to navigate to the most relevant category for your website.</li>
<li>On that category page, click the &quot;Suggest&quot; on the top right hand corner to suggest your website. 
Follow the directions carefully to complete your submission.</li>
<li>It is important that you complete the profile information about your business to the greatest extent possible.</li>
</ul>',
			'effort'      => '30',
			'impact'      => '20',
			'weight'      => '1',
		),
		'18' => array(
			'title'       => '<p>Submit your site to <a href="http://pegasusdirectory.com">Pegasus Directory</a>.</p>',
			'description' => '<p><a href="http://pegasusdirectory.com">Pegasus Directory</a> is a free web directory with hand-picked links chosen by editors. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to a website. </p>',
			'howTo'       => '<p><a href="http://pegasusdirectory.com/submit.php/free_listing.html">Click here</a> to begin submitting your site to Pegasus Directory. It is important that you complete the  information about your business to the greatest extent possible. Getting a free Pegasus link has a wait time of one week to six months.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '10',
		),
		'19' => array(
			'title'       => '<p>Ask for links from colleagues, friends, and business partners.</p>',
			'description' => '<p>Contact specific friends, vendors, suppliers, and clients, and ask them to consider linking to your website if their website has a &quot;resources&quot; web page. This is a great way to showcase your website on more listings. Websites that display competitors will very likely advertise your site as well.  Relationship-building is a popular way to build links.</p>',
			'howTo'       => '<p>If you have not already done so, reach out to people you know who have websites (friends, family, colleagues, acquaintances, etc.), or reach out to sites consistent with your specialty. Choose websites that have a similar target audience as your own. </p>

<p>[CLICK HERE TO E-MAIL YOUR LINK REQUEST](mailto:enter@email.address?body=Hello, I would like for you to consider linking to my website. We have a lot of content that we think your visitors would find useful, such as: [fill in content here].</p>

<p>Please be sure to personalize your email as much as possible, as it will dramatically increase the likelihood that someone will create a link to your site. Also, keep at it! If one try doesn\'t work, don\'t give up. </p>',
			'effort'      => '60',
			'impact'      => '20',
			'weight'      => '15',
		),
		'20' => array(
			'title'       => '<p>Write and submit a testimonial for a favorite product or vendor that can provide a quality link back to your site.</p>',
			'description' => '<p>Submitting a testimonial for a product or vendor that you\'ve liked or admired is a great way to earn a valuable link back to your site. Offer to write a testimonial for a product or vendor and have it published on the vendor\'s site that you admire. </p>

<p>Users who read candid reviews and testimonials are more likely to be interested in products that have real-life endorsements.</p>

<p>Product or vendor testimonials are beneficial marketing material for small businesses. Product reviews posted on websites encourage credibility, trust, and support. Not only that, writing material for people in your industry encourages them to write content for your own site as well. Word-of-mouth marketing is a wonderful strategy to take on and conquer.</p>

<p>In addition, this optimization process will also help you to meet other users in your industry and/or online marketing.</p>',
			'howTo'       => '<p>Follow these tips to help you write and submit a testimonial:</p>

<ul>
<li><p>Write down the various service providers and products your business uses. Relationship examples include accountants, web hosting companies, credit card processing companies, monthly subscriptions, and marketing services businesses. </p></li>
<li><p>After you have a full list, review those business websites to see if there are opportunities to submit a testimonial. Be sure that you are comfortable with a site and see its value before taking any next steps.</p></li>
<li><p>If you are ready to move forward, draft and send an email to the business, and be sure to mention your offer to write a testimonial.</p></li>
<li><p>When writing your testimonial, don\'t forget to include a link back to your website to get the inbound link.</p></li>
</ul>

<p>[Click here to email someone and offer a testimonial]&lt;a href=\'mailto:enter@email.address?body=Hello, I have really enjoyed using your service and would be willing to write a testimonial if you would like. It has been great working with your business, and I want to help promote your business any way I can.\'</p>',
			'effort'      => '60',
			'impact'      => '10',
			'weight'      => '16',
		),
		'21' => array(
			'title'       => '<p>Submit your site to <a href="http://www.brownbook.net/">Brownbook.net</a>.</p>',
			'description' => '<p><a href="http://www.brownbook.net">Brownbook.net</a> is a free web directory. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to a website. To learn more details about the Brownbook directory,  <a href="http://www.brownbook.net/free_listing.html">click this link</a> as well as <a href="http://www.brownbook.net/info_about.html">this link</a>.</p>',
			'howTo'       => '<p>Add your business to Brownbook.net by clicking  <a href="http://www.brownbook.net/business/add/">here</a>. </p>

<p>It is important that you complete the profile information about your business to the greatest extent possible. Try to include your targeted keywords when you can, and be sure to provide a link back to your website.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '2',
		),
		'22' => array(
			'title'       => '<p>Submit your site to <a href="http://www.jayde.com">Jayde.com</a>.</p>',
			'description' => '<p><a href="http://www.jayde.com">Jayde.com</a> is a free web directory with hand-picked links chosen by editor. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to a website. To learn more details about the Jayde directory,  <a href="http://www.jayde.com/about">click here</a>.</p>',
			'howTo'       => '<p><a href="http://www.jayde.com/submit.html">Click here</a> to begin listing your site on Jayde.com. Businesses can apply for a listing using Jayde\'s submission form or Facebook application. It is important that you complete the profile information about your business to the greatest extent possible.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '3',
		),
		'23' => array(
			'title'       => '<p>Submit your site to the <a href="http://wordpress.org/showcase">Wordpress Showcase</a>.</p>',
			'description' => '<p><a href="wordpress.org/showcase">The Wordpress Showcase</a> is a free web directory for you to list your Wordpress website. Its goal is to demonstrate quality design examples of Wordpress websites to businesses as a publishing platform. Selections in the Wordpress Showcase are hand-chosen chosen by editors. If you manage your website using the Wordpress platform, this is a great source for a permanent, relevant link to your website. </p>

<p><a href="http://wordpress.org/showcase/archives/">Click here</a> to view a full archive list of all the websites in the Wordpress Showcase Directory. </p>',
			'howTo'       => '<p><a href="http://wordpress.org/showcase/submit-a-wordpress-site/">Click here</a> to begin submitting your site to the Wordpress Showcase. It is important that you complete the information about your business to the greatest extent possible. Please note: the editors will need to consider your site as high quality work for it to be admitted into the Showcase.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '5',
		),
		'24' => array(
			'title'       => '<p>Submit your site to <a href="http://www.scrubtheweb.com">ScrubTheWeb</a>.</p>',
			'description' => '<p><a href="http://www.scrubtheweb.com">ScrubTheWeb</a> is a free web directory. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to a website. <a href="http://www.scrubtheweb.com">ScrubTheWeb</a> helps businesses gain high rankings and hopes to bring fast and relevant search results.</p>',
			'howTo'       => '<p><a href="http://www.scrubtheweb.com/addurl.html">Click here</a> to submit your website to ScrubTheWeb. You can also add your site to Scrubtheweb.com using the <a href="http://www.scrubtheweb.com/abs/submit">Easy Submit</a> pathway. </p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '6',
		),
		'25' => array(
			'title'       => '<p>Submit your site to <a href="http://searchsight.com">Searchsight.com</a>.</p>',
			'description' => '<p><a href="http://searchsight.com">Searchsight.com</a> is a free web directory with hand-picked links chosen by editors. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to a website.  To read more about <a href="http://searchsight.com">Searchsight.com</a>, <a href="http://searchsight.com/about.htm">click here</a>.</p>',
			'howTo'       => '<p><a href="http://searchsight.com/submit.htm">Click here</a> to begin listing your site on SearchSight.com. It is important that you complete the profile information about your business to the greatest extent possible. Try to include your targeted keywords when you can. To learn more details and tips about how to submit your site to the SearchSight directory,  <a href="http://searchsight.com/faqs.htm">click here</a>.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '8',
		),
	),
	'93'  => array(
		'26' => array(
			'title'       => '<p>Create a <a href="http://www.youtube.com">YouTube</a> channel for your business.</p>',
			'description' => '<p><a href="http://www.youtube.com">YouTube</a> is the largest video sharing network on the web today. Posting and sharing videos on YouTube\'s large platform is a great, free way to advertise and create quality links and buzz back to your site. </p>

<p>You will want to set up a YouTube account and channel for your business. YouTube does not have accounts targeted specifically toward small businesses, but you are encouraged to create a free YouTube channel for your business.</p>

<p><a href="http://www.youtube.com/t/about_youtube">Click here</a> to learn more details about YouTube and to <a href="http://www.youtube.com/t/about_essentials#discover">discover some of its capabilities</a>.</p>',
			'howTo'       => '<p><a href="http://www.youtube.com/signup">Click here</a> to create your YouTube channel.</p>

<ul>
<li><p>You will automatically be taken to a Google account creation page. If you already have a Google account, sign in now. </p></li>
<li><p>It is important that you complete the YouTube profile page information about your business to the greatest extent possible. Personalizing and branding the page with your business is recommended. </p></li>
<li><p>Overall, the more videos you can upload, the better. The videos should be made with quality and care.</p></li>
<li><p>As a quick tip: Begin by adding whatever videos you\'ve completed in the past before creating new ones. Whether you are advertising a specific product, a promotion, how to perform a specific procedure, etc., there are so many options for videos! </p></li>
<li><p>Create content that will help or boost your audience. Valuable video content that satisfies your audience\'s needs is crucial (More ideas for video creation will follow in future courses).</p></li>
<li><p>Be sure to add keywords to your title, tag, and descriptions for your videos, which allows them to they be easily found on the web and therefore better optimized for search results. </p></li>
<li><p>After posting a video on YouTube, make sure that you always share it on your social sites and blog right after. This step should be a build-in step of the video set-up process. Share your video socially as soon as it\'s posted.</p></li>
<li><p>For more information about YouTube to get started, refer to some additional best practices and tips <a href="http://support.google.com/youtube/bin/topic.py?hl=en&amp;topic=165560">here</a> and <a href="http://www.youtube.com/t/about_getting_started">here</a>.</p></li>
</ul>',
			'effort'      => '30',
			'impact'      => '30',
			'weight'      => '1',
		),
		'27' => array(
			'title'       => '<p>Create an account and recommend relevant content on <a href="http://www.stumbleupon.com/">StumbleUpon</a>.</p>',
			'description' => '<p><a href="http://www.stumbleupon.com/">StumbleUpon</a> is a quality website for links to be submitted, shared, and &quot;stumbled.&quot; The site allow its large community of users to discover, categorize, rate, and share useful websites based on your personal preferences. It is a great tool to promote yourself as an authority by sharing relevant information and eventually promoting information about your business. This sharing activity elevates your online visibility and overall relevancy. </p>',
			'howTo'       => '<p>To get started, go to the <a href="http://www.stumbleupon.com/">StumbleUpon homepage</a>, and click the &quot;Join for Free&quot; icon.</p>

<ul>
<li><p>Fill in the details and select the &quot;Get Started&quot; button.</p></li>
<li><p>Then, choose the relevant topic from the given keywords and proceed further by clicking on &quot;Start Stumbling.&quot;</p></li>
<li><p>Continue following the directions to stumble and post your website.</p></li>
<li><p>Click <a href="http://www.stumbleupon.com/aboutus/">this link</a> for more information on the features of StumbleUpon.</p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '10',
		),
		'28' => array(
			'title'       => '<p>Create an account at <a href="http://www.Pinterest.com/">Pinterest</a> for your business.</p>',
			'description' => '<p><a href="http://www.Pinterest.com/">Pinterest</a> is a popular social sharing website that allows one to create, organize and share images found on the web using pinboards. Pinterest is a great tool to promote yourself as an authority by sharing relevant images and eventually promoting images related to your business. </p>

<p>This sharing activity elevates your online visibility and overall relevancy. <a href="http://business.pinterest.com/">Click here</a> for more information on Pinterest for business.</p>',
			'howTo'       => '<p>Begin the process of creating your business\' Pinterest page by <a href="https://pinterest.com/business/create/">clicking here</a>.</p>

<ul>
<li><p>If you already have a Pinterest account and you need to convert your existing account to a business account, <a href="https://pinterest.com/login/?next=/business/convert">click here</a>.</p></li>
<li><p>All pins that you create should link back to your website.</p></li>
<li><p>It is important that you complete the profile information about your business to the greatest extent possible. Personalizing and branding the page with your business is recommended. </p></li>
<li><p>Be sure to include keywords in your titles, tags, and descriptions for your pins so that they can be easily found on the web and therefore better optimized for search results. Your descriptions should be short and to the point.</p></li>
<li><p>Your pins should describe your business as much as you can. Create many boards to describe your business, target audience, likes, features of your website, and goals. </p></li>
<li><p>Add a pin icon to your website, and make sure to post any featured content on Pinterest along with your other social sharing sites, such as LinkedIn, Facebook and Twitter.</p></li>
<li><p>We\'d recommend following back pinners who choose to follow you. </p></li>
<li><p>For more information about Pinterest to get started, refer to this <a href="http://pinterest.com/about/help/">help section</a>. </p></li>
</ul>',
			'effort'      => '30',
			'impact'      => '10',
			'weight'      => '11',
		),
		'29' => array(
			'title'       => '<p>Create an account at <a href="http://www.Locr.com/">Locr</a>.</p>',
			'description' => '<p><a href="http://www.Locr.com/">Locr</a> is a free photo sharing website that allows users to manage, tag, organize, and share photos with locations. This photo community specializes in geotagging, which links photos to locations on maps.  It is a great tool to promote yourself as an authority by sharing relevant images and eventually promoting images related to your business. This sharing activity elevates your online visibility and overall relevancy.</p>

<p>To optimize the presence of your website on search engines, Locr is recommended for your advanced local search and social media strategy. Click <a href="http://www.locr.com/learn-more/">this link</a> for more information on the features of Locr.</p>',
			'howTo'       => '<p>Begin the process of signing up for Locr by <a href="https://www.locr.com/signup">clicking here</a>. </p>

<ul>
<li><p>Be sure to geotag some photos of your business as well as images that relate to your industry and nearby location.</p></li>
<li><p>We\'d recommend taging and describing your photos with keywords so that they can bebetter optimized for search results. Your descriptions should be short and to the point.</p></li>
<li><p>Click <a href="http://www.locr.com/help">this link</a> for more help to get started with Locr. </p></li>
<li><p>There is also a helpful <a href="http://www.locr.com/forum">forum</a> for users to get tips.</p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '12',
		),
		'30' => array(
			'title'       => '<p>Create an account at <a href="http://www.Flickr.com/">Flickr</a>.</p>',
			'description' => '<p><a href="http://www.Flickr.com/">Flickr</a> is a free photo sharing website that allows users to manage, tag, organize, and  share photos. This photo community is a powerfully designed photo management tool and optimizes the presence of your website on search engines. </p>

<p>It is a great tool to promote yourself as an authority by sharing relevant images and eventually promoting images related to your business. This sharing activity elevates your online visibility and overall relevancy. Click <a href="http://www.flickr.com/tour//">this tour link</a> for more information on the features of Flickr.</p>',
			'howTo'       => '<p>Begin the process of signing up for Flickr by <a href="http://www.flickr.com/">clicking here</a>.  You will need to sign into Yahoo, Google, or Facebook in order to start up a Flickr account.</p>

<ul>
<li><p>Be sure to upload some photos of your business as well as images that relate to your industry.</p></li>
<li><p>We\'d recommend taging and describing your photos with keywords so they can be better optimized for search results. Your photo descriptions should be short and to the point.</p></li>
<li><p>Click <a href="http://www.flickr.com/help/forum/en-us/">this link</a> for more help to get started with Locr. </p></li>
<li><p>Use the <a href="http://www.flickr.com/help">Help Section</a> for any questions you have.</p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '13',
		),
		'31' => array(
			'title'       => '<p>Create an account at <a href="http://www.smugmug.com/">SmugMug</a>.</p>',
			'description' => '<p><a href="http://www.smugmug.com/">SmugMug</a> is a photo sharing website that allows users to manage, tag, organize, and share photos, and should optimize the presence of your website on search engines. It is a great tool to promote yourself as an authority by sharing relevant images and eventually promoting images related to your business. This sharing activity elevates your online visibility and overall relevancy.</p>

<p>Click <a href="http://www.smugmug.com/about/">this link</a> for more information on the features of SmugMug.</p>',
			'howTo'       => '<p>Begin the process of signing up for SmugMug by <a href="https://secure.smugmug.com/signup.mg">clicking here</a>. </p>

<ul>
<li><p>The site offers a 14 day free trial for you to decide which of their plans is the best for your business.</p></li>
<li><p>Be sure to upload some photos of your business as well as images that relate to your industry and nearby location.</p></li>
<li><p>We\'d recommend taging and describing your photos with keywords so that they can be better optimized for search results. Your photo descriptions should be short and to the point.</p></li>
<li><p>Click <a href="http://help.smugmug.com/customer/portal/topics/314787-business-best-practices/articles">this link</a> for more help on how to optimize SmugMug for your business.</p></li>
<li><p>There is also a <a href="http://help.smugmug.com/customer/portal/topics/287372-getting-started-on-smugmug/articles">Getting Started section</a> for users.</p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '14',
		),
		'32' => array(
			'title'       => '<p>Create an account at <a href="http://www.Panoramio.com/">Panoramio</a>..</p>',
			'description' => '<p><a href="http://www.Panoramio.com/">Panoramio</a> is a free photo sharing website that allows users to manage, tag, organize, and share photos. Panoramio specializes in sharing places and locations across the world. To optimize the presence of your website on search engines, Panoramio is recommended for your advanced local search and social media strategy. </p>',
			'howTo'       => '<p>Begin the process of signing up for Panoramio by <a href="http://www.panoramio.com/">clicking here</a>.  You will need to have a Google account so that you can access Panoramio through Google\'s various applications.</p>

<ul>
<li><p>Be sure to upload some photos of your business\' location.Make sure the photos adhere to the website\'s policies before posting and photos.</p></li>
<li><p>We\'d recommend taging and describing your photos with keywords so that they can be better optimized for search results. Your photo descriptions should be short and to the point.</p></li>
<li><p>Click <a href="http://www.panoramio.com/help">this link</a> for more help on how to best use Panoramio.</p></li>
<li><p>There is also a helpful <a href="http://www.panoramio.com/forums">forum for users</a>.</p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '15',
		),
		'33' => array(
			'title'       => '<p>Create an account at <a href="http://www.Vimeo.com">Vimeo</a>.</p>',
			'description' => '<p>Founded by a group of film makers, <a href="http://www.Vimeo.com">Vimeo</a> is a video sharing network on the web. Posting and sharing videos on the web is a great, free way to advertise and create quality links and buzz back to your site.</p>

<p><a href="http://vimeo.com/about">Click here</a> to learn more details about Vimeo.</p>',
			'howTo'       => '<p><a href="http://vimeo.com/join">Click here</a> to set up your Vimeo account.</p>

<ul>
<li><p>Vimeo offers many options for quality and customization when you publish your videos. </p></li>
<li><p>The more videos you can upload, the better. As a quick tip: Begin by adding whatever videos you\'ve completed in the past before creating new ones. Whether you are advertising a specific product, a promotion, how to perform a specific procedure, etc., there are so many options for videos! Create content that will help or boost your audience. Valuable video content that satisfies your audience\'s needs is crucial (more on this later).</p></li>
<li><p>Be sure to add keywords to your title, tag, and descriptions for your videos so that they can be easily found on the web and therefore better optimized for search results. </p></li>
<li><p>After posting a video on Vimeo, make sure that you always share it on your social sites and blog right after. This step should be a build-in step of the video set-up process. Share your video socially as soon as it\'s posted (more on this later).</p></li>
<li><p>For more information about Vimeo to get started, refer to some additional best practices and tips <a href="http://vimeo.com/help">Click here</a> and you can also <a href="http://vimeo.com/help/faq">check out the FAQ</a>.</p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '2',
		),
		'34' => array(
			'title'       => '<p>Create an account at <a href="http://www.dailymotion.com">DailyMotion</a>.</p>',
			'description' => '<p><a href="http://www.dailymotion.com">DailyMotion</a> is a video sharing network on the web. Posting and sharing videos on the web is a great, free way to advertise and create quality links and buzz back to your site. <a href="http://www.dailymotion.com/us/about">Click here</a> to learn more details about DailyMotion.</p>',
			'howTo'       => '<p><a href="http://www.dailymotion.com/register">Click here</a> to begin setting up your DailyMotion account. Here are some more quick tips to help you with your DailyMotion account:</p>

<ul>
<li><p>DailyMotion specializes in 34 localized versions so be sure to check if your location is listed.</p></li>
<li><p>The more videos you can upload, the better. As a quick tip: Begin by adding whatever videos you\'ve completed in the past before creating new ones. Whether you are advertising a specific product, a promotion, how to perform a specific procedure, etc., there are so many options for videos! Create content that will help or boost your audience. Valuable video content that satisfies your audience\'s needs is crucial.</p></li>
<li><p>Be sure to add keywords to your title, tag, and descriptions for your videos so that they can be easily found on the web and therefore better optimized for search results. </p></li>
<li><p>After posting a video on DailyMotion make sure that you always share it on your social sites and blog right after. This step should be a build-in step of the video set-up process. Share your video socially as soon as it\'s posted.</p></li>
<li><p>You can check out the <a href="http://www.dailymotion.com/us/faq">FAQ</a> for more helpful tips.</p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '3',
		),
		'35' => array(
			'title'       => '<p>Create an account at <a href="http://www.metacafe.com">MetaCafe</a>.</p>',
			'description' => '<p><a href="http://www.metacafe.com">MetaCafe</a> is a short-form video sharing network on the web. Posting and sharing videos on the web is a great, free way to advertise and create quality links and buzz back to your site. <a href="http://www.metacafe.com/aboutUs/">Click here</a> to learn more details about MetaCafe.</p>',
			'howTo'       => '<p><a href="https://secure.metacafe.com/account/login/?token=8f246cb07463564338206813ae355983&amp;action=login">Click here</a> to begin setting up your MetaCafe account .</p>

<ul>
<li><p>The more videos you can upload, the better. The videos should be made with quality and care. As a quick tip: Begin by adding whatever videos you\'ve completed in the past before creating new ones. Whether you are advertising a specific product, a promotion, how to perform a specific procedure, etc., there are so many options for videos! Create content that will help or boost your audience. Valuable video content that satisfies your audience\'s needs is crucial.</p></li>
<li><p>Be sure to add keywords to your title, tag, and descriptions for your videos so that they can be easily found on the web and therefore better optimized for search results. </p></li>
<li><p>After posting a video on MetaCafe make sure that you always share it on your social sites and blog right after. This step should be a build-in step of the video set-up process. Share your video socially as soon as it\'s posted.</p></li>
<li><p>Be sure to also check out MetaCafe\'s <a href="http://www.metacafe.com/privacy/#Submission_Rules">submission guidelines</a> before uploading any videos for your business. </p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '4',
		),
		'36' => array(
			'title'       => '<p>Create an account and recommend relevant articles at <a href="http://www.Scoop.it">Scoop.it</a>.</p>',
			'description' => '<p><a href="http://www.Scoop.it">Scoop.it </a> is a popular recommendation and discovery site for your business. This website site allows users to discover, categorize, and share useful content and information. It is a good source to accumulate relevant and quality links to a website. Recommending relevant information to people via Scoop.it is a great way to promote yourself online and increase your visibility. We\'ll provide some opportunities to promote some of your own information in later courses.</p>',
			'howTo'       => '<p><a href="&quot;http://www.Scoop.it">Click here</a>  to sign up with Scoop.it if you have a Facebook, Twitter, or LinkedIn account (which you should!). If you don\'t have a social media account to sign up with, <a href="https://www.scoop.it/subscribe?pc=Business&amp;token=&amp;sn=&amp;showForm=true">click here</a>. </p>

<ul>
<li><p>Choose the content that you curate carefully. You will want to pick topics that relate to your business and industry.  Be sure to include keywords that relate to your business.</p></li>
<li><p>You can read <a href="http://feedback.scoop.it/knowledgebase">the FAQ</a> for more information on what Scoop.it is all about.</p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '5',
		),
		'37' => array(
			'title'       => '<p>Create an account and recommend relevant articles on <a href="http://www.delicious.com">Delicious</a>.</p>',
			'description' => '<p>Founded in 2003 and acquired by Yahoo! in 20011, <a href="http://www.delicious.com">Delicious</a> is a popular discovery and social bookmarking site. Social bookmarking sites allow users to discover, categorize, and share useful content and information. They are great tools to promote yourself as an authority by sharing relevant information and eventually promoting information about your business. This sharing activity elevates your online visibility and overall relevancy. </p>

<p> <a href="http://delicious.com/about/">Click here</a> to learn more details about Delicious.</p>',
			'howTo'       => '<p>You will first need to register an account at <a href="https://delicious.com/join">Delicious</a> to get started.</p>

<ul>
<li><p>Consider bookmarking your site and a few other useful websites or specific articles in your industry, so that other users will find your Delicious account\'s collection valuable.  </p></li>
<li><p>You can read <a href="http://delicious.com/help">this guide</a> for more information on what Delicious is all about.</p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '6',
		),
		'38' => array(
			'title'       => '<p>Create an account and recommend relevant content on <a href="http://www.diigo.com">Diigo</a>.</p>',
			'description' => '<p> <a href="http://www.diigo.com">Diigo</a> stands for &quot;Digest of Internet Information, Groups and Other stuff.&quot; Diigo is a quality social bookmarking site. Social bookmarking sites allow users to discover, categorize, and share useful content and information. </p>

<p>They are great tools to promote yourself as an authority by sharing relevant information and eventually promoting information about your business. This sharing activity elevates your online visibility and overall relevancy. </p>

<p><a href="http://www.diigo.com/learn_more">Click here</a> to learn more details about Diigo.</p>',
			'howTo'       => '<p>Register an account at <a href="https://secure.diigo.com/sign-up?referInfo=http%3A%2F%2Fwww.diigo.com">Diigo</a> to get started. </p>

<ul>
<li><p>Consider bookmarking your site and a few other useful websites or specific articles in your industry, so that other users will find your Diigo account\'s bookmarks valuable. </p></li>
<li><p>You can read through the <a href="http://feedback.diigo.com/forums/76211-ideas">user forum</a> or check out the <a href="http://help.diigo.com">help section</a> for more information on Diigo.</p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '7',
		),
		'39' => array(
			'title'       => '<p>Create an account and recommend relevant content on <a href="http://http://www.reddit.com/">Reddit</a>.</p>',
			'description' => '<p><a href="http://www.reddit.com/">Reddit.com</a> is an opensource website that encourages users to create communities, post content, vote on links, and share comments. This online community allows users to discover, categorize, and share useful content and information. </p>

<p>They are great tools to promote yourself as an authority by sharing relevant information and eventually promoting information about your business. This sharing activity elevates your online visibility and overall relevancy.  <a href="http://www.reddit.com/about/">Click here</a> to learn more details about Reddit.</p>',
			'howTo'       => '<ul>
<li><p>First, you will need to register an account with <a href="http://www.reddit.com/">Reddit</a> and then entirely fill out the form.</p></li>
<li><p>Consider submitting your site and a few other useful websites or specific articles in your industry, so that other users will find your Reddit account\'s bookmarks valuable. </p></li>
<li><p>You can read <a href="http://www.reddit.com/help/faq">this FAQ</a> for more information on how to use Reddit. </p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '8',
		),
		'40' => array(
			'title'       => '<p>Create an account and recommend relevant content on <a href="http://www.digg.com">Digg</a>.</p>',
			'description' => '<p><a href="http://www.digg.com">Digg</a> is a quality website for users to post content, vote on links, and publish comments. The site allow its large community of users to discover, categorize, rate, and share useful articles and content on the web. They are great tools to promote yourself as an authority by sharing relevant information and eventually promoting information about your business. This sharing activity elevates your online visibility and overall relevancy. </p>

<p>Click <a href="http://about.digg.com/">this link</a> for more information on the features of Digg.</p>',
			'howTo'       => '<p>To get started, <a href="http://www.digg.com">Join Digg</a>. </p>

<ul>
<li><p>Then, click on &quot;Continue to My News.&quot; </p></li>
<li><p>Submit your url link and click &quot;Digg It.&quot;</p></li>
<li><p>Continue following the directions to post your content.</p></li>
<li><p>Consider selecting a specific article of yours or a blog post featured on your site, as well as a few other useful websites or specific articles in your industry. With this strategy, other users will find your account\'s other links valuable.</p></li>
<li><p>If you post an article or blog post that\'s featured on your website, this would ensure the link will stay live. We\'re starting to see more and more that Digg will remove it if the link simply goes to a business website.</p></li>
<li><p>Click to view the <a href="http://www.digg.com/faq/">FAQ</a> for more information on Digg.</p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '9',
		),
	),
	'94'  => array(
		'41' => array(
			'title'       => '<p>Submit your site to <a href="http://www.local.yahoo.com">Yahoo! Local</a> using <a href="http://beta.listings.local.yahoo.com/?refsrc=ysb-mybiz_addbiz">Add a Business</a>.</p>',
			'description' => '<p>When searching for businesses online, most users will search in their nearby location. <a href="http://www.local.yahoo.com">Yahoo! Local</a> is a free online local listings directory and a great way to increase your site\'s local exposure. </p>

<p>Your listed business information is featured in Yahoo! Local searches, as well as other Yahoo! search engines, such as Yahoo! Answers.</p>',
			'howTo'       => '<ul>
<li>Log in to your Yahoo! account <a href="https://login.yahoo.com">here</a>.</li>
<li>Then, <a href="http://beta.listings.local.yahoo.com/?refsrc=ysb-mybiz_addbiz">click</a> to access Yahoo! Local and submit a free listing.</li>
<li>After your site is activated, it should appear in Yahoo search results within 5 business days.</li>
<li>Click <a href="http://help.yahoo.com/l/us/yahoo/ysm/ll/basics/basics-05.html">here</a> for Yahoo! Local Listings requirements.</li>
</ul>',
			'effort'      => '15',
			'impact'      => '50',
			'weight'      => '1',
		),
		'42' => array(
			'title'       => '<p>Submit your site to <a href="http://www.insiderpages.com">InsiderPages</a>.</p>',
			'description' => '<p><a href="http://www.insiderpages.com">InsiderPages.com</a> is a directory for local search listings and reviews. The site allows users in a community to search for businesses and services by name, category, or location.</p>',
			'howTo'       => '<p>Click <a href="http://www.insiderpages.com/session/new">here</a> to create an InsiderPages.com account, and then you can add your business listing.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '10',
		),
		'43' => array(
			'title'       => '<p>Submit your site to <a href="http://merchantcircle.com">MerchantCircle</a>.</p>',
			'description' => '<p><a href="http://www.merchantcircle.com/corporate/">MerchantCircle.com</a> is the largest network of local businesses on the Internet with more than 1.4 million businesses. Business owners can promote their business by creating a profile, uploading pictures, writing blogs, publicizing events, creating coupons and newsletters, and connecting with other merchants, all for free. </p>

<p>MerchantCircle profiles typically rank well within search engines and also provide a link back to your site, which helps with your link building efforts.</p>',
			'howTo'       => '<p>Creating a profile at MerchantCircle is very simple. Just go to <a href="http://www.merchantcircle.com/corporate/">MerchantCircle.com</a> to sign up.</p>

<p>Consider the following tips to get the most out of MerchantCircle from an SEO perspective:</p>

<ul>
<li>Get your profile completion rate to 100%, and make sure the business name and contact information are exactly the same across various profile sites.</li>
<li>Make sure your business name and phone number are exactly the way you want them to appear. MerchantCircle creates a URL with the business name and phone number, and updating this later will likely cause you to lose rank in search engines for your MerchantCircle profile.</li>
<li>Create connections with other business. It is not only helpful from a networking standpoint. Why not collaborate with a similar business in a different city? This also helps build up your profile in the eyes of MerchantCircle and can help with getting your profile more prominently ranked.</li>
<li>Leverage the blogging tool as a quick and easy way to create more content, increasing the overall authority of the profile page.</li>
</ul>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '11',
		),
		'44' => array(
			'title'       => '<p>Submit your site to <a href="http://local.botw.org/helpcenter/jumpstartproduct.aspx">Best of the Web Local</a>.</p>',
			'description' => '<p>Millions of users search the Internet on a daily to find local businesses.  The <a href="http://local.botw.org/helpcenter/jumpstartproduct.aspx">BOTW Local Business Directory</a> has a low-cost local listing service that will increase your online visibility and drive more visitors to your store.</p>

<p>Listed business information is featured in BOTW Local searches and categories.  Best of the Web Business profile pages also rank well in major search engines, like Google and Bing, and these profile pages be citation sources for Google Places.</p>',
			'howTo'       => '<p>Click <a href="http://local.botw.org/helpcenter/jumpstartproduct.aspx">here</a> to add your business listing to Best of the Web Local. You can sign up for the premium ($9.95/month) or jumpstart ($99.95/year) listing.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '12',
		),
		'45' => array(
			'title'       => '<p>Submit your site to <a href="https://www.bingplaces.com/">Bing Places for Business</a>.</p>',
			'description' => '<p>When searching for something online, most users will search for a business in their nearby location. <a href="https://www.bingplaces.com/">Bing Places for Business</a> is an online local listings directory and a great way to increase your site\'s local exposure. </p>

<ul>
<li>The displayed business information is featured in Bing Places searches and other Bing search engines.</li>
<li>Posting your business on Bing early on. Since this directory recently relaunched , it is a great way to gain credibility and lift your ranking.</li>
</ul>',
			'howTo'       => '<p>Click <a href="https://www.bingplaces.com/DashBoard">here</a> to begin adding your listing to Bing Places for Business. </p>',
			'effort'      => '15',
			'impact'      => '50',
			'weight'      => '2',
		),
		'46' => array(
			'title'       => '<p>Submit your site to <a href="http://www.yelp.com">Yelp</a> using the <a href="http://www.yelp.com/business">Yelp Business Owner\'s Guide</a>. </p>',
			'description' => '<p><a href="http://www.yelp.com">Yelp</a> is a large local business and services directory that allows business owners and users to share information and their experiences. </p>

<ul>
<li>The site is highly recommended for your local search strategy. Yelp receives nearly 25 million monthly visitors who are customers looking to buy and find the right spot for them.</li>
<li>Yelp is simple to navigate, and the homepage always features fresh, new content.</li>
</ul>',
			'howTo'       => '<p>Click <a href="https://biz.yelp.com/signup">here</a> to get started, and follow the on-screen instructions to submit your business listing to Yelp.</p>

<ul>
<li>After your Yelp business listing goes live, be sure to listen, stay engaged and communicate with users. You are welcome to advertise, reply, and/or respond to conversations, but do not actively solicit reviews.</li>
<li>Also, be sure to fill in the &quot;About This Business&quot; profile with as much information as you can, including but not limited to accurate hours, address, phone number, business photos, etc.</li>
<li>Yelp offers various free services for businesses that are listed on the site so be sure to check out all their services.</li>
</ul>',
			'effort'      => '15',
			'impact'      => '50',
			'weight'      => '3',
		),
		'47' => array(
			'title'       => '<p>Submit your site to <a href="http://localeze.com">Localeze</a>.</p>',
			'description' => '<p><a href="http://localeze.com">Localeze</a> is a service that syndicates local business listings to major local search engines and databases. By listing a website with Localeze, it will then be distributed to hundreds of of solid local search engines and databases that are syndicated by Localeze.</p>',
			'howTo'       => '<p>Click <a href="http://www.neustarlocaleze.biz/directory/index.aspx">here</a> and follow the instructions to add a paid business listing to Localeze. The cost is around $25/month for an annual contract. It is important that you complete the profile information about your business to the greatest extent possible. </p>',
			'effort'      => '15',
			'impact'      => '40',
			'weight'      => '4',
		),
		'48' => array(
			'title'       => '<p>Submit your site to <a href="http://leads.infousa.com/Landing/UpdateListing.aspx">InfoUSA</a>.</p>',
			'description' => '<p><a href="http://www.infousa.com/Home/Index/190000/S32047621931619">InfoUSA</a> is a provider of both business and consumer information and marketing solutions. Many search engines leverage infoUSA for business information. Therefore, it is important to make sure infoUSA has your correct business information.</p>',
			'howTo'       => '<p>Click <a href="https://listings.expressupdateusa.com/Account/Register">here</a> to create an account with InfoUSA in order to be able to add your business listing to its local directory - ExpressUpdateUSA.com. It is important that you complete the profile information about your business to the greatest extent possible. </p>',
			'effort'      => '15',
			'impact'      => '40',
			'weight'      => '5',
		),
		'49' => array(
			'title'       => '<p>Submit your site to <a href="http://www.yellowpages.com">Yellowpages.com</a>.</p>',
			'description' => '<p><a href="http://www.yellowpages.com">Yellowpages.com</a> is a local and national business search directory and is the online version of the Yellow Pages catalog.</p>

<p>YellowPages.com, which is part of the large <a href="http://corporate.yp.com/">YP</a> platform, features local businesses, product and services reviews, directions and maps, and mobile on-the-go usage. It is also significant to note that YellowPages sees more than 100 million monthly business searches. As one of the largest local directories today, it continues to stay relevant in this ever-changing world of local marketing. </p>',
			'howTo'       => '<p>Submitting your website takes only a few minutes and doesn?t require payment. Click <a href="https://adsolutions.yp.com/SSO/Register">here</a> to add your business listing to YellowPages for free.</p>',
			'effort'      => '15',
			'impact'      => '25',
			'weight'      => '6',
		),
		'50' => array(
			'title'       => '<p>Submit your site to <a href="http://www.citysearch.com">CitySearch</a>.</p>',
			'description' => '<p><a href="http://www.citysearch">CitySearch</a> is a search directory for city businesses and services. The community site focuses on user advice and reviews for local listings. CitySearch is now run through CityGrid and also owns <a href="&quot;http://www.insiderpages.com">InsiderPages</a>. </p>',
			'howTo'       => '<p>Click <a href="https://signup.citygrid.com/">here</a> to add your business listing to CitySearch. To join CitySearch, you will be redirected to sign up via CityGrid, which CitySearch recently launched in order to aggregate and expand all of CitySearch\'s local listings and content.  </p>',
			'effort'      => '15',
			'impact'      => '25',
			'weight'      => '7',
		),
		'51' => array(
			'title'       => '<p>Submit your site to <a href="http://www.superpages.com">Superpages.com</a>.</p>',
			'description' => '<p><a href="http://www.superpages">Superpages.com</a> is a local and national search directory for businesses and retailers. The Superpages directory specializes in local business information user recommendations and reviews, as well as directions and maps.</p>',
			'howTo'       => '<p>Click <a href="http://www.supermedia.com/spportal/quickbpflow.do">here</a> to begin adding your business listing to Superpages. It is important that you complete the profile information about your business to the greatest extent possible. </p>',
			'effort'      => '15',
			'impact'      => '20',
			'weight'      => '8',
		),
		'52' => array(
			'title'       => '<p>Submit your site to <a href="http://www.manta.com/">Manta.com</a>.</p>',
			'description' => '<p><a href="http://www.manta.com/">Manta.com</a> is a free business directory and one of the fastest growing small business resources in the U.S. </p>',
			'howTo'       => '<p>Click <a href="http://www.manta.com/profile/my-companies/select">here</a> to add your business listing to Manta.com. It is a quick and easy process to add your business to Manta.com. Be sure to include your website address in step two of the setup.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '9',
		),
	),
	'96'  => array(
		'53' => array(
			'title'       => '<p>Don\'t buy or sell links to or from your website.</p>',
			'description' => '<p>Some websites sell static HTML links on their pages. It is also relatively common for some websites to buy links from other sites to help boost their search engine rankings.</p>

<p>We recommend removing any links you may be selling or links you are currently buying because it is not allowed by <a href="http://support.google.com/webmasters/bin/answer.py?hl=en&amp;answer=35769">search engine guidelines</a>. You can actually hamper your rankings on search engines by engaging in this practice. </p>',
			'howTo'       => '<p>If you are buying or selling paid links for search engine ranking benefit, stop!</p>

<p>If you don\'t know what we\'re talking about, you\'re in the clear! Go ahead and mark this as <strong>&quot;Complete&quot;</strong>. </p>',
			'effort'      => '15',
			'impact'      => '50',
			'weight'      => '1',
		),
		'54' => array(
			'title'       => '<p>Install Yoast SEO plugins on your Wordpress website.</p>',
			'description' => '<p>Yoast SEO is the most comprehensive plugin for helping you optimize your WordPress website. It has over 1 million downloads and is completely free. Some of its features include post titles and meta descriptions setting up, meta robots configuration, canonicalization of pages, XML sitemaps, robots.txt and .htaccess editing, head section editing, breadcrumbs setting, and many more.</p>',
			'howTo'       => '<ul>
<li>Download the plugin <a href="https://wordpress.org/plugins/wordpress-seo/">here</a></li>
<li>In your WordPress dashboard (wp-admin) under the &quot;Plugins&quot; menu go to &quot;Add New&quot;.</li>
<li>Click &quot;Upload&quot; and select the plugin .zip file that you just downloaded.</li>
<li>Click &quot;Install Now&quot; and you are done!</li>
</ul>

<p>You can use the plugin to configure different search engine related features of your website.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '10',
		),
		'55' => array(
			'title'       => '<p>Insert YOUR KEYWORD in the &lt;TITLE&gt; of your homepage.</p>',
			'description' => '<p>A title of a web page tells users and search engines what the topic of a particular site is.  The title is found at the top of the browser.  In HTML code, the &lt;title&gt; can be found within the &lt;head&gt; tags of the HTML document.</p>

<p>The title of the page is a key factor for how a page is ranked within search engines.  The title of the page also serves as the title that displays in the snippet or text that shows up on search engines results.</p>

<p>The title tag for your homepage should describe your site\'s specific content and include your primary keyword, but it should be very brief.</p>',
			'howTo'       => '<p>To view your current title for the homepage, look at the text at the top of the browser of your homepage.&lt;br/&gt;&lt;br/&gt;</p>

<p>In your WordPress administration section, expand the &quot;Settings&quot; menu and then click &quot;All in One SEO&quot;. </p>

<p>While you\'re here, make sure the plugin is enabled with the &quot;Plugin Status&quot; option. </p>

<p>Fill in the &quot;Home Title Field.&quot; When you\'re finished, scroll to the bottom of the page and click &quot;Update Options.&quot; </p>

<p>When using titles, apply the following best practices.&lt;br/&gt;</p>

<p>&lt;ol&gt;</p>

<p>&lt;li&gt;Choose a title that effectively communicates the topic of the page&amp;#8217;s content.&lt;/li&gt;</p>

<p>&lt;li&gt;Include your business name at the beginning or end of the title.&lt;/li&gt;  </p>

<p>&lt;li&gt;Include your target keyword(s) for the page including YOUR KEYWORD.&lt;/li&gt;</p>

<p>&lt;li&gt;If you are targeting a local market, you should also include the state or city of your target market&lt;/li&gt;</p>

<p>&lt;li&gt;Use dividers or dashes to combine all of the above elements.  For example, a simple structure could be &amp;lt;Business Name&amp;gt; | &amp;lt;Target Keyword&amp;gt; - in &amp;lt;Location&amp;gt;&lt;/li&gt;</p>

<p>&lt;li&gt;Try to limit to 5-12 words, and keep the title under the 70 characters, so the full title appears in the snippet on the engine.&lt;/li&gt;</p>

<p>&lt;/ol&gt;</p>',
			'effort'      => '15',
			'impact'      => '50',
			'weight'      => '12',
		),
		'56' => array(
			'title'       => '<p>Insert YOUR KEYWORD in the &lt;BODY&gt; of your homepage.</p>',
			'description' => '<p>The body of your webpage contains the main content of the page, such as text, images, and links. In the HTML file itself, this information is found within the tags. Primary and secondary keywords in the body of your homepage can help to enhance your page position in search engines.</p>',
			'howTo'       => '<p>In your WordPress administration section, navigate to the area where you edit the content of your home page. This may be in &quot;Pages&quot; or in &quot;Appearance&quot; -&gt; &quot;Widgets&quot;. Make sure the text contains  in one or more areas of the page.</p>',
			'effort'      => '10',
			'impact'      => '40',
			'weight'      => '13',
		),
		'61' => array(
			'title'       => '<p>Place your U.S. address in standard format in the footer of your website. </p>',
			'description' => '<p>Search engines favor local businesses that list a street address on their website. </p>

<ul>
<li>Sites should display a physical address rather than simply a photo  since search engines can only &quot;read&quot; textual content.</li>
<li>It is very important for search engines to see your business name, address (including state and zip), and phone number as they crawl your site.</li>
</ul>',
			'howTo'       => '<p>Include a standard format U.S. address on your site. Make sure to list your full address (street name, city, state, zip code) in readable text. Ideally this is included in a global footer so it appears on every page of your site.</p>',
			'effort'      => '15',
			'impact'      => '25',
			'weight'      => '18',
		),
		'62' => array(
			'title'       => '<p>Insert a local phone number in the footer on your site.</p>',
			'description' => '<p>Inserting a phone number on your website improves visibility of your geographic area (via the area code) to search engines and local directories.</p>

<p>Local area codes in phone numbers can help ranking in local search engines. Include both local number and 800# when necessary.</p>',
			'howTo'       => '<p>If the DIYSEO spider did not find a phone number, please add it to the site. Make sure to list your full phone number (including area code) in plain text.</p>',
			'effort'      => '15',
			'impact'      => '50',
			'weight'      => '19',
		),
		'63' => array(
			'title'       => '<p>Confirm that your website has long-term domain registration.</p>',
			'description' => '<p>Long-term domain registration is the time period that you are the registered owner of a domain. Long-term domain registration is a indicator to search engines around the quality and relevancy of your website. Google has acknowledged that registering your domain for 2+ years or more as a trust signal.</p>',
			'howTo'       => '<p>You can extend the number of years a domain name registration is active through your domain name registrar. It is important to visit your domain registrar\'s website and make sure that the expiration for your site is not less than 3 months and is ideally 2+ years. </p>',
			'effort'      => '15',
			'impact'      => '40',
			'weight'      => '2',
		),
		'64' => array(
			'title'       => '<p>Do not link to &quot;dead&quot; pages from your website.</p>',
			'description' => '<p>Dead pages are also defined as \'broken\' pages. These are the links on a page of a site that lead nowhere.</p>

<p>Dead pages return a 404 error message when you click on them. This means the page no longer exists on the web. It may have been taken down by the host or moved to a different URL.</p>',
			'howTo'       => '<p>Run your site through this <a href="http://www.brokenlinkcheck.com/">\'broken\' link
checker</a>. It will tell you which links no longer work. If you find broken links, go into your website and remove or update all of the dead links.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '20',
		),
		'65' => array(
			'title'       => '<p>Make sure all content on your website is unique.</p>',
			'description' => '<p>Having unique content on your website is one of the most important factors in contemporary search engine optimization. Major penalties might occur if search engines find that some of the information on your website is same as information found elsewhere on the web (unless you provide clear reference to the source).  After the <a href="http://en.wikipedia.org/wiki/Google_Panda">Google Panda</a> algorithm update uniqueness of content on site became essential part of the Internet marketing of each business.</p>',
			'howTo'       => '<p>Click <a href="http://www.virante.org/seo-tools/duplicate-content">here</a> and insert your domain in the box. If no duplicate content is found, you are good to go! If some duplicate content is found though, you have to make sure you either completely remove it from your website, or you substitute it with unique content.</p>',
			'effort'      => '15',
			'impact'      => '25',
			'weight'      => '3',
		),
		'66' => array(
			'title'       => '<p>Confirm that your home page has a high-quality meta description.</p>',
			'description' => '<p>The meta description is designed to provide a brief description of your page which can be used by search engines or directories. The meta description has little impact on your search engine ranking but it significant influences the likelihood that a person will click on the your listing in the search engine results and visit your site.</p>

<p>The meta description takes the following form: &lt;meta name=&quot;description&quot; content=&quot;Brief description of the contents of your page.&quot;&gt;</p>',
			'howTo'       => '<p>To edit the home page\'s meta description, in your WordPress administration section, navigate to &quot;Settings&quot; and then click &quot;All in One SEO.&quot; Next, enter your meta description in the &quot;&quot;Home Description&quot;&quot; field, and then click &quot;Update Options&quot; at the bottom.&lt;br/&gt;&lt;br/&gt;</p>

<p>To edit any other page or post\'s meta description, navigate to &quot;Pages&quot; or &quot;Posts,&quot; click on the page you want to edit, scroll down to the All in One SEO Pack options box, and then fill in the &quot;Description&quot; field. Save the page when you\'re done.</p>',
			'effort'      => '10',
			'impact'      => '15',
			'weight'      => '4',
		),
		'67' => array(
			'title'       => '<p>Create an account at <a href="http://www.google.com/webmasters/tools">Google Webmaster</a>.</p>',
			'description' => '<p><a href="http://www.google.com/webmasters/tools">Google Webmaster</a> provides a free and easy way to make your website more Google-friendly. See your website the way Google sees it:</p>

<ul>
<li>View which of your pages are included in Google\'s index</li>
<li>See any errors encountered while Google attempted to crawl or database your site</li>
<li>Find search queries that list your site as a result</li>
<li>Find out which sites link to yours</li>
</ul>',
			'howTo'       => '<p>To get setup with Google Webmaster click <a href="http://www.google.com/webmasters/tools">here</a>, submit your sites, and start getting data in your Webmaster Dashboard.</p>

<p><a href="http://support.google.com/webmasters/?hl=en">Click here</a> for more information. </p>',
			'effort'      => '30',
			'impact'      => '30',
			'weight'      => '5',
		),
		'68' => array(
			'title'       => '<p>Create an account at <a href="http://www.google.com/analytics/">Google Analytics</a>.</p>',
			'description' => '<p>Google Analytics is a free web analytics solution that gives you insights into your website traffic and, if applicable, online conversions. It is relatively easy to implement and provides many benefits to your online marketing initiatives.</p>',
			'howTo'       => '<p>Refer to this <a href="http://www.google.com/analytics/discover_analytics.html">checklist</a>  to get Google Analytics set up for your business.</p>',
			'effort'      => '30',
			'impact'      => '30',
			'weight'      => '6',
		),
		'69' => array(
			'title'       => '<p>Create an account at <a href="http://www.bing.com/toolbox/webmasters/">Bing Webmaster</a>.</p>',
			'description' => '<p>Similar to Google Webmaster, the Bing Webmaster account allows you to understand how your site looks to Bing as it relates to crawlability and links to your site.</p>

<p>Find out more about Bing Webmaster <a href="http://www.bing.com/toolbox/webmaster">here</a>.</p>

<p>When you own a website, it is essential to keep it consistently optimized and ranked in search engines. Owning a Bing Webmaster Tools account will help businesses better understand their website?s performance, from crawlability to links status to overall search engine presence. The Bing toolbox hopes to ultimately drive an increase of free Bing and Yahoo traffic to your site.</p>',
			'howTo'       => '<p>To begin, sign into your Bing <a href="http://upcity.com/blog/2013/06/creating-an-account-at-bing-webmaster-tool/">account</a>.</p>

<p>Then, verify your website here by adding your site <a href="http://www.bing.com/toolbox/webmaster">here</a> and start to get information into your Bing Webmaster Dashboard.</p>

<p>Please note: You will need to download the Silverlight software to use Bing Webmaster.</p>

<p>What Is Next:
When your Bing Webmaster account is confirmed, check out the Reports and Data section as well as the Diagnostics &amp; Tools section to create personalized  reports.</p>

<p>View all the website data in any selected time period, including clicks, geo-targeting, pages crawled and indexed, and error reporting.</p>

<p>You will also begin to receive SEO progress reporting to help increase traffic and ultimately improve your web conversions.</p>',
			'effort'      => '30',
			'impact'      => '30',
			'weight'      => '7',
		),
		'70' => array(
			'title'       => '<p>Confirm that you are redirecting to either www.YOURDOMAIN.com or YOURDOMAIN.com.</p>',
			'description' => '<p>When you own a domain, you can actually have your site show up for two web addresses:</p>

<p>www.YOURDOMAIN.com or YOURDOMAIN.com</p>

<p>For the purposes of search engine optimization, you want to take users and search engines to one web address. You should select either www.YOURDOMAIN.com or YOURDOMAIN.com and then have one site &quot;redirect&quot; to the other. </p>

<p>For example, if you want users and search engines to all go to www.YOURDOMAIN.com, you would have YOURDOMAIN.com redirect to www.YOURDOMAIN.com. In this scenario, if a user types in &quot;YOURDOMAIN.com&quot; into the web address of a browser they will actually get taken to  www.YOURDOMAIN.com.</p>',
			'howTo'       => '<p>In order to do a redirect from one version of your web address to the other, you need to do a &quot;301 Redirect.&quot; </p>

<p>This is a pretty technical process and you should reach out to either your website designer or contact support related to your content management system (CMS).</p>

<p>If you would like to try it on your own, here are two articles that provide some how-to instructions:</p>

<p><a href="http://www.dailyblogtips.com/how-to-setup-a-301-redirect/">Daily Blog Tips - How to setup a 301 redirect</a>.</p>

<p><a href="http://www.bruceclay.com/blog/2007/03/how-to-properly-implement-a-301-redirect/">Bruce Clay - How to implement a 301 redirect</a>.</p>

<p>Please contact us if you have any questions on this.</p>',
			'effort'      => '60',
			'impact'      => '25',
			'weight'      => '8',
		),
		'71' => array(
			'title'       => '<p>In your WordPress system, turn on Wordpress SEF (search engine friendly URLs).</p>',
			'description' => '<p>By default WordPress uses web URLs which have question marks and lots of numbers in them. However, WordPress offers you the ability to create custom URL structure for your permalinks and archives. This can improve the aesthetics, usability, and forward-compatibility of your links. Additionally, as keywords in page URL are a ranking factor in the organic search results, setting up search engine friendly URLs for your WordPress website would help boost your search engine rankings.</p>',
			'howTo'       => '<p>Log in to your WordPress dashboard (wp-admin) and from the left side menu go to Settings. You should see the sub-section &quot;Permalinks&quot;. Under it you would be able to choose the structure of your permalinks. Set it to &quot;Post name.&quot;</p>',
			'effort'      => '15',
			'impact'      => '40',
			'weight'      => '9',
		),
	),
	'97'  => array(
		'72' => array(
			'title'       => '<p>Submit your site to <a href="http://www.foursquare.com">Foursquare</a>.</p>',
			'description' => '<p><a href="http://www.foursquare.com">Foursquare</a> is a free and popular application that encourages its users to share and record their locations, get recommendations of places to visit, and earn promotions based on where  they\'ve visited. </p>

<ul>
<li><p>Foursquare describes itself as a companion for yourself and your friends wherever you may go. Foursquare encourages customers to recommend businesses and promotions in their nearby location.</p></li>
<li><p>You will need to claim your business and contact information in order for your potential customers to find you on FourSquare and for you to gain more customers.</p></li>
<li><p>This type of local website is a great way to improve your business\' local rank and will increase your chances to show up more prominently in search engines. In addition, it will help you to engage with your target audience and get more visibility.</p></li>
<li><p>To learn more details about FourSquare,  <a href="https://foursquare.com/about">click here</a>.</p></li>
</ul>',
			'howTo'       => '<p>Begin the process by signing up for a Foursquare personal account <a href="https://foursquare.com/signup/">here</a>. </p>

<ul>
<li><p>Click <a href="http://business.foursquare.com/business-tools/claim-your-business">here</a> to claim your business\' FourSquare listing.</p></li>
<li><p>You will need to make sure that the business information (for each of your locations) is correct.  As with other profiles, you will need to complete all available fields. It is important that you fill in the profile information about your business to the greatest extent possible. </p></li>
<li><p>Visit <a href="http://www.foursquare.com/business"> this link</a> to learn more about how your local business can use FourSquare effectively.</p></li>
<li><p>Foursquare also provides detailed sections with  <a href="http://support.foursquare.com/entries/21320766-claiming-your-locations-on-foursquare">step-by-step instructions</a> and <a href="http://support.foursquare.com/entries/22408416-best-practices-updating-your-business-listing">best practices</a> as well. </p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '20',
			'weight'      => '1',
		),
		'73' => array(
			'title'       => '<p>Submit your site to <a href="http://CitySquares.com">CitySquares</a>.</p>',
			'description' => '<p><a href="http://CitySquares.com">CitySquares</a> is a local directory that you can list your business. Adding your business information on local directories like CitySquares helps improve your local visibility and elevates your overall relevancy in the eyes of Google and other search engines. </p>

<p>This is a great way to improve your website\'s local rank and will increase your chances to show up more prominently in search engines. To learn more details about the CitySquares directory, <a href="http://citysquares.com/corporate/about">click here</a>. </p>',
			'howTo'       => '<p>Click <a href="http://my.citysquares.com/search">here</a> to begin adding your business to CitySquares. Follow the directions carefully to complete your submission. It is important that you complete the profile information about your business to the greatest extent possible. IMPORTANT: Be sure to provide a link to your website.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '10',
		),
		'74' => array(
			'title'       => '<p>Submit your site to <a href="http://www.GoMyLocal.com">GoMyLocal</a>.</p>',
			'description' => '<p><a href="http://www.GoMyLocal.com">GoMyLocal</a> is a local directory that you can list your business. Adding your business information on local directories like GoMyLocal helps improve your local visibility and elevates your overall relevancy in the eyes of Google and other search engines. </p>

<p>This is a great way to improve your website\'s local rank and will increase your chances to show up more prominently in search engines. To learn more details about the GoMyLocal directory, <a href="http://www.gomylocal.com/aboutus">click here</a>. </p>',
			'howTo'       => '<p>Click <a href="https://www.gomylocal.com/add_listings.php?action=register&amp;option=4">here</a> to begin adding your business to GoMyLocal. Follow the directions carefully to complete your submission. It is important that you complete the profile information about your business to the greatest extent possible. </p>

<p>IMPORTANT: Be sure to provide a link to your website. To learn more details about how this local directory can help grow your business, <a href="http://www.gomylocal.com/faq">click here</a>.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '11',
		),
		'75' => array(
			'title'       => '<p>Submit your site to <a href="http://www.mybusinesslistingmanager.com//">Acxiom MyBusinessListingManager</a>.</p>',
			'description' => '<p><a href="http://www.mybusinesslistingmanager.com">Acxiom MyBusinessListingManager</a> provides local information to a number of online directories.  It is important that your information is accurate in Acxiom so that the sites that take in their data does not share bad information. </p>

<p>Listing with Acxiom is a great way to improve your business\'s local rank and will increase your chances to show up more prominently in search engine local rankings. To learn more details about the Acxiom directory, <a href="http://www.mybusinesslistingmanager.com/Manage/LearnMore">click here</a>. </p>',
			'howTo'       => '<p>Click <a href="http://www.mybusinesslistingmanager.com/Manage/Claim">here</a> to begin adding your business to Acxiom. Follow the directions carefully to complete your submission and look up your business. It is important that you complete the profile information about your business to the greatest extent possible.</p>

<p>To learn more details about how this local directory can help grow your business, <a href="http://www.mybusinesslistingmanager.com/documents/MBLM_FAQ_07272012.pdf">click here</a>.</p>',
			'effort'      => '30',
			'impact'      => '20',
			'weight'      => '12',
		),
		'76' => array(
			'title'       => '<p>Submit your site to <a href="http://business.intuit.com/directory/growBusiness.jsp">Intuit Business Directory</a>.</p>',
			'description' => '<p><a href="http://business.intuit.com/directory/growBusiness.jsp">Intuit Business Directory</a> is a free directory for businesses that allows business submissions and also pulls listings from websites like infoUSA and CitySearch. It is important to claim your listing, make sure the information is correct, and track customer reviews.</p>',
			'howTo'       => '<p>Click <a href="http://business.intuit.com/directory/growBusiness.jsp">here</a> to add a business listing to Intuit Business Directory. You will need to enter your business\'s phone number to get started with the listing.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '13',
		),
		'77' => array(
			'title'       => '<p>Submit your site to <a href="http://www.hotfrog.com">HotFrog</a>.</p>',
			'description' => '<p><a href="http://www.hotfrog.com">HotFrog</a> is a free local directory that you can list your business. Adding your business information on local directories like HotFrog helps improve your local visibility and elevates your overall relevancy in the eyes of Google and other search engines.</p>

<p>This is a great way to improve your website\'s local rank and will increase your chances to show up more prominently in search engines. </p>',
			'howTo'       => '<p>Click <a href="http://www.hotfrog.com/AddYourBusiness.aspx">here</a> to begin claiming your Hotfrog listing. Follow the directions carefully to complete your submission. </p>

<p>It is important that you complete the profile information about your business to the greatest extent possible. IMPORTANT: Be sure to provide a link to your website. To learn more details about how HotFrog can help grow your business,  <a href="http://sbh.hotfrog.com/Topics/Growing-Your-Business">click here</a>.</p>',
			'effort'      => '15',
			'impact'      => '20',
			'weight'      => '2',
		),
		'78' => array(
			'title'       => '<p>Submit your site to <a href="http://www.mapquest.com">MapQuest</a>.</p>',
			'description' => '<p><a href="http://www.mapquest.com">MapQuest</a> is a local directory that you can list your business. Adding your business information on local directories like MapQuest helps improve your local visibility and elevates your overall relevancy in the eyes of Google and other search engines. As a geographically targeted directory, MapQuest?s local listings help to increase a business?s overall SEO authority and value. </p>

<p> This is a great way to improve your site\'s local rank and will increase your chances to show up more prominently in search engines. To learn more details about MapQuest\'s Business Listings, <a href="https://listings.mapquest.com/apps/listing">click here</a>.</p>',
			'howTo'       => '<p>Yext has partnered with MapQuest to create listings for your business. Click <a href="https://listings.mapquest.com/pl/mapquest-claims/preview.html">here</a> to begin claiming your MapQuest listing. </p>

<p>Follow the directions carefully to complete your submission. It is important that you complete the profile information about your business to the greatest extent possible. IMPORTANT: Be sure to provide a link to your website. You can also upgrade to a premium package when you sign up. </p>',
			'effort'      => '15',
			'impact'      => '20',
			'weight'      => '3',
		),
		'79' => array(
			'title'       => '<p>Submit your site to <a href="http://www.local.com">Local.com</a>.</p>',
			'description' => '<p><a href="http://www.local.com">Local.com</a> is a free local directory to list your business.  The site contains more than 16 million business listings in every U.S. zip code, which provide useful information on hours of operation, reviews, events, offered services, and more. </p>

<p>Customers can visit the website from any mobile phone or device in order to get information on their favorite local enterprises. As a local business, it is imperative that customers access your listings from a variety of mediums.</p>

<p>Local.com is a high quality directory that adds positive value to your online presence. The site also garners traffic from users looking to purchase products and services. When users create an account, they can add engaging photos, submit a link to your website, and more!</p>',
			'howTo'       => '<p>Click <a href="http://www.local.com/">here</a> to begin creating your Local.com account. Head to the bottom of the page and click ?Claim Your Business Listing.? Follow the directions carefully to complete your submission. </p>

<p>It is important that you complete the profile information about your business to the greatest extent possible.</p>

<p>To learn more details about how Local.com can help grow your business, visit the  <a href="http://www.local.com/faq.aspx">FAQ page</a>.</p>',
			'effort'      => '15',
			'impact'      => '20',
			'weight'      => '4',
		),
		'80' => array(
			'title'       => '<p>Submit your site to  <a href="http://www.yellowbot.com">Yellowbot</a>.</p>',
			'description' => '<p><a href="http://www.yellowbot.com">Yellowbot</a> is a free local directory that you can list your business.  Adding your business information on local directories like Yellowbot helps improve your local visibility and elevates your overall relevancy in the eyes of Google and other search engines..</p>

<p>This is a great way to improve your site\'s local rank and will increase your chances to show up more prominently in search engines. To learn more details about this directory, <a href="http://www.yellowbot.com/about/about.html">click here</a>. </p>',
			'howTo'       => '<p>Click <a href="https://www.yellowbot.com/signin?yp_r=http%3A%2F%2Fwww.yellowbot.com%2Fsubmit%2Fnewbusiness">here</a> to begin adding your business to YellowBot. Follow the directions carefully to complete your submission. It is important that you complete the profile information about your business to the greatest extent possible. IMPORTANT: Be sure to provide a link to your website.</p>',
			'effort'      => '15',
			'impact'      => '15',
			'weight'      => '5',
		),
		'81' => array(
			'title'       => '<p>Sign up for <a href="http://listings.local.yahoo.com/enh.php">Yahoo! Local Enhanced Listing</a>.</p>',
			'description' => '<p><a href="http://listings.local.yahoo.com/enh.php">Yahoo! Local Enhanced Listing</a> costs an additional $9.95 a month on top of your free Yahoo! Local profile listing. We recommend this piad listing for three important reasons:</p>

<ul>
<li><p>It has shown that an enhanced listing improves your rank within Yahoo! Local.</p></li>
<li><p>This listing allows you to add more content that can help your Yahoo! profile show up more prominently in all search engines.</p></li>
<li><p>This listing provides an area for three links that you can use to link back to specific pages on your site or one of your other profiles (ex. MerchantCircle).</p></li>
</ul>',
			'howTo'       => '<p>Click <a href="http://listings.local.yahoo.com/enh.php">here</a> to get your Yahoo! Local Enhanced Listing. Consider the following tips to get the full value from this listing:</p>

<ul>
<li><p>As with other profiles, complete all available fields.</p></li>
<li><p>Use all three link locations to create links to pages on your website or other prominent profiles (Google Pages, MerchantCircle, Yelp, etc.).</p></li>
<li><p>Be sure to use your targeted keywords as the names of your links. For example, instead of using www.merchantcircle.com/mybusinessname as the text of the link, use Chicago Dentist as the text with the link still linking to your MerchantCircle profile.</p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '20',
			'weight'      => '53',
		),
		'82' => array(
			'title'       => '<p>Submit your site to <a href="http://local.botw.org/helpcenter/premiumproduct.aspx?uid=44833">Best of the Web Local</a>.</p>',
			'description' => '<p><a href="http://local.botw.org/helpcenter/premiumproduct.aspx?uid=44833">Best of the Web Local</a> is a quality local business search directory. Millions of users search the Internet on a daily to find local businesses. The BOTW Local Business Directory has a low-cost local listing service that will increase your online visibility and drive more visitors to your store.</p>

<p>Listed business information is featured in BOTW Local searches and categories.  Best of the Web Business profile pages also rank well in major search engines, like Google and Bing, and these profile pages be citation sources for Google Places. The relevant listings offers business owners and viewers a clean categorization of their websites.</p>',
			'howTo'       => '<p>Click <a href="http://local.botw.org/helpcenter/jumpstartproduct.aspx">here</a> to add your business listing to Best of the Web Local. Click the Sign Up! button on the homepage to get started. </p>

<p>The directory offers two versions of access: Jumpstart and Premium listings. The Jumpstart listings require a simple signup process in addition to a fee of $1.95 per month. The Premium listings cost $9.95 per month or $99.95 a year, and offers the ability to list your best selling brands, link to your business website, and other features.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '54',
		),
		'83' => array(
			'title'       => '<p>Submit your site to <a href="http://www.judysbook.com">Judy\'s Book</a>.</p>',
			'description' => '<p><a href="http://www.judysbook.com">Judy\'s Book</a> is a local directory that you can list your business. Judy?s Book is one of the best social search directory tools for users get trusted reviews on local businesses and places. Searchers can get valuable information through recommendations, find coupons, and search for deals. The site offers more than 4.5 million reviews at your fingertips.</p>

<p>Adding your business information on local directories like Judy\'s Book helps you to gain more potential customers. Trusted local directories are a great way to improve your website\'s local rank, which will increase your chances to show up more prominently in search engines.</p>

<p><a href="http://www.judysbook.com/Biz">Judy\'s Book</a> targets small businesses by offering a link to your website, a profile and keywords. For more information about Judy\'s Book, visit the <a href="http://www.judysbook.com/About.aspx">About Section</a> and <a href="http://www.judysbook.com/help">FAQ section</a>.</p>

<p>Exclusive promo for members: Type in the coupon code of JudysBook20 to receive 20% off your listing! (Promo Expires February 2015)</p>',
			'howTo'       => '<p><a href="http://www.judysbook.com/">Click here</a> to claim your
Judy\'s Book listing. You will need to sign up for a Judy\'s Book account to proceed. Follow the directions carefully to complete your submission. It is important that you fill in the profile information about your business to the greatest extent possible.</p>

<p>You can sign up for a basic listing ($29.99/month) or pro listing ($99.99/year). </p>

<p>Exclusive promo for members: Type in the coupon code of JudysBook20 to receive 20% off your listing! (Promo Expires February 2015)</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '55',
		),
		'84' => array(
			'title'       => '<p>Submit your site to <a href="http://www.dexknows.com/">Dex Knows</a>.</p>',
			'description' => '<p><a href="http://www.dexknows.com">DexKnows.com</a> is a local directory to help your business get found by local search customers and to generate high quality links. DexKnows is a part of <a href="http://www.dexone.com/company">Dex One</a>, which provides a variety of web and print marketing for businesses.  </p>

<p><a href="http://www.dexknows.com">DexKnows.com</a> offers an Enhanced Pack and a Starter Pack. Prices may vary depending on your marketing.</p>',
			'howTo'       => '<p>Submitting your website takes only a few minutes, and you can choose between a free listing or two payment packages. <a href="http://www.dexone.com/solutions/local-directories#DexKnows">Click here</a> to begin signing up your free business listing for DexKnows.com.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '56',
		),
		'85' => array(
			'title'       => '<p>Submit your site to <a href="http://www.kudzu.com/">Kudzu</a>.</p>',
			'description' => '<p><a href="http://www.kudzu.com/">Kudzu</a> is a local directory to list your business. The listings specialize in service providers for your family, health, and home. <a href="http://www.kudzu.com/browse.do">Click here</a> for a full list of business listings. </p>',
			'howTo'       => '<p>Submitting your website on Kudzu takes only a few minutes, and you can choose between a free or paid profile. Click here to add your business listing to <a href="https://register.kudzu.com/packageSelect.do">Kudzu.com</a>.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '57',
		),
		'86' => array(
			'title'       => '<p>Submit your site to <a href="http://ShowMeLocal.com">ShowMeLocal.com</a>.</p>',
			'description' => '<p><a href="http://ShowMeLocal.com">ShowMeLocal.com</a> is a free local directory that you can list your business.  Adding your business information on local directories like ShowMeLocal helps improve your local visibility and elevates your overall relevancy in the eyes of Google and other search engines..</p>

<p>This is a great way to improve your site\'s local rank and will increase your chances to show up more prominently in search engines. To learn more details about the ShowMeLocal.com directory, <a href="http://www.showmelocal.com/about/l">click here</a>. </p>',
			'howTo'       => '<p>Click <a href="http://www.showmelocal.com/businessregistration.aspx">here</a> to begin creating your ShowMeLocal business account. Follow the directions carefully to complete your submission. </p>

<p>It is important that you complete the profile information about your business to the greatest extent possible. IMPORTANT: Be sure to provide a link to your website. To learn more details about how ShowMeLocal.com can help grow your business,  <a href="http://www.showmelocal.com/faq">visit the FAQ section</a>.</p>',
			'effort'      => '15',
			'impact'      => '15',
			'weight'      => '6',
		),
		'87' => array(
			'title'       => '<p>Submit your site to <a href="http://www.EZLocal.com">EZlocal</a>.</p>',
			'description' => '<p><a href="http://www.EZLocal.com">EZlocal</a>  is a free local directory that you can list your business.  Adding your business information on local directories like EZLocal helps improve your local visibility and elevates your overall relevancy in the eyes of Google and other search engines.</p>

<p>This is a great way to improve your site\'s local rank and will increase your chances to show up more prominently in search engines. To learn more details about the EZlocal.com directory, <a href="http://ezlocal.com/about/default.aspx">click here</a>. </p>',
			'howTo'       => '<p>Click <a href="https://secure.ezlocal.com/newbusiness/find.aspx">here</a> to begin creating your EZlocal account. Follow the directions carefully to complete your submission. It is important that you complete the profile information about your business to the greatest extent possible. IMPORTANT: Be sure to provide a link to your website.</p>

<p>To learn more details about how this local directory can help grow your business,  <a href="http://ezlocal.com/about/LocalMapsOptimization.aspx">click here</a>.</p>',
			'effort'      => '15',
			'impact'      => '15',
			'weight'      => '7',
		),
		'88' => array(
			'title'       => '<p>Submit your site to <a href="http://Yellowee.com">Yellowee</a>.</p>',
			'description' => '<p><a href="http://Yellowee.com">Yellowee</a>  is a free local directory that you can list your business. Adding your business information on local directories like Yellowee helps improve your website\'s local visibility and elevates your overall relevancy in the eyes of Google and other search engines. </p>',
			'howTo'       => '<p>Click <a href="http://biz.yellowee.com/steps/find-your-business/">here</a> to begin adding your business listing to Yellowee. Follow the directions carefully to search for your listing and complete your submission. It is important that you complete the profile information about your business to the greatest extent possible. IMPORTANT: Be sure to provide a link to your website.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '8',
		),
		'89' => array(
			'title'       => '<p>Submit your site to  <a href="http://MagicYellow.com">MagicYellow</a>.</p>',
			'description' => '<p><a href="http://MagicYellow.com">MagicYellow</a>  is a free local directory that you can list your business. Adding your business information on local directories like MagicYellow  helps improve your local visibility and elevates your overall relevancy from a local perspective. </p>

<p>This is a great way to improve your website\'s local rank and will increase your chances to show up more prominently in search engines.</p>',
			'howTo'       => '<p>Click <a href="http://www.magicyellow.com/add-your-business.cfm">here</a> to begin adding your local business to MagicYellow. You will start by entering your business phone number. </p>

<p>Follow the directions carefully to complete your submission. It is important that you complete the profile information about your business to the greatest extent possible. IMPORTANT: Be sure to provide a link to your website.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '9',
		),
	),
	'99'  => array(
		'90'  => array(
			'title'       => '<p>BLOG - Create an RSS feed for your blog.</p>',
			'description' => '<p>RSS stands for Really Simple Syndication. A RSS feed allows people to sign up for the latest news from select blogs or other content providers. RSS pulls together content from different sources via a feed reader and \'feeds\' it to the subscriber as it happens.</p>',
			'howTo'       => '<p>Google\'s Feedburner is one of the most popular RSS options. Learn how to add it to your blog <a href="http://feedburner.google.com/">here</a>.</p>',
			'effort'      => '30',
			'impact'      => '15',
			'weight'      => '10',
		),
		'91'  => array(
			'title'       => '<p>CONTACT US - Create a Contact Us page for your website.</p>',
			'description' => '<p>Make it easy for people to contact you. Always include a <strong>Contact Us</strong> web page on your site. This does not have to be in the top navigation unless having them email or call is a primary goal of your site. At an minimum, have an easy to find link in the footer.</p>',
			'howTo'       => '<p>Include all the numbers and ways people can reach you such as: phone (provide direct lines and extensions as appropriate), address, email, fax. Also include the hours people can reach you if relevant to your business.</p>',
			'effort'      => '60',
			'impact'      => '50',
			'weight'      => '11',
		),
		'92'  => array(
			'title'       => '<p>CONTACT US - Place your address on Contact Us page.</p>',
			'description' => '<p>Placing your physical address on your site is both user and search engine friendly.</p>',
			'howTo'       => '<p>In your CMS, add a standard format US address to the <strong>Contact Us</strong> page.</p>',
			'effort'      => '15',
			'impact'      => '25',
			'weight'      => '12',
		),
		'93'  => array(
			'title'       => '<p>CONTACT US - Provide directions to your business address Contact Us page.</p>',
			'description' => '<p>Providing directions to your business is user and search engine friendly. Textual directions that reference major streets and local landmarks can also help your business potentially show up in results for location specific searches.</p>',
			'howTo'       => '<p>Directions to find your business\' geographic location should be listed on your site\'s contact page, directions page, or the page where your address appears. </p>

<p>Directions to your location should be in textual format, written by yourself, and should name specific roads and addresses with zip codes. </p>

<p>When linking to a location page, be specific with the anchor text, i.e. write &quot;click here for directions to Iowa City, IA, Books and More&quot; instead of writing &quot;Click here for directions.&quot;</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '13',
		),
		'94'  => array(
			'title'       => '<p>CONTACT US - List all the cities and towns that your business covers on the Contact Us page.</p>',
			'description' => '<p>Include a listing all the cities and towns that your business serves. Placing this text on a website will help your business appear in the results for  geo-specific searches.</p>',
			'howTo'       => '<p>List the cities and towns that your business covers on your site. Be very descriptive when listing the various locations that your business serves. An example of this step is &quot;We provide services in the Central Ohio area, including Columbus, Zanesville, Delaware and Grove City.&quot;</p>

<p>If your business is in a small city, make sure to clarify all the cities and states that your business covers.</p>',
			'effort'      => '15',
			'impact'      => '50',
			'weight'      => '14',
		),
		'95'  => array(
			'title'       => '<p>CONTACT US - Host or link to your street address through <a href="http://maps.google.com">Google Maps</a>.</p>',
			'description' => '<p>Providing a link to your exact address helps boost visibility in &lt;<a href="http://maps.google.com">Google Map</a> </p>

<p>Today\'s mapping technology provides integration with most GPS technology on mobile phones making it easy for customers on-the-go to find you.</p>',
			'howTo'       => '<p>Sign into your Google Account and list your local business in their <a href="https://maps.google.com/">map section</a>. It will ask for your phone to
see if you are already in the mapping system.</p>

<p>Adding a Google map to your website is easy. Follow <a href="http://maps.google.com/help/maps/getmaps/plot-one.html">simple directions</a> to get a variety of map options and HTML code for your site.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '15',
		),
		'96'  => array(
			'title'       => '<p>ABOUT US - Create an About Us page.</p>',
			'description' => '<p>An <strong>About Us</strong> page tells customers what your company is about and who runs it. It\'s an opportunity to highlight your expertise and qualifications to add credibility to your business.</p>',
			'howTo'       => '<p>Add a page to top or secondary navigation so customers can easily learn more about you and your business. Add a page through your CMS or website as instructed.</p>',
			'effort'      => '60',
			'impact'      => '50',
			'weight'      => '16',
		),
		'97'  => array(
			'title'       => '<p>ABOUT US - Add information about your company and about yourself on your About Us page.</p>',
			'description' => '<p>An <strong>About Us</strong> page tells customers what your company is about and who runs it. It\'s an opportunity to highlight your expertise and qualifications to add credibility to your business.</p>',
			'howTo'       => '<p>Provide information about your company that is relevant to your business and important to your customers. If unsure what to add, start with the basicsâ€”who, what, where, when, and why. Upload to your CMS as instructed.</p>',
			'effort'      => '30',
			'impact'      => '50',
			'weight'      => '17',
		),
		'98'  => array(
			'title'       => '<p>ABOUT US - Add a photo of your office and/or of yourself and your team on your About Us page.</p>',
			'description' => '<p>People like to know who they are working with. A photo of you or your team shows that you are real people, working in the area.</p>',
			'howTo'       => '<p>In your CMS, upload an image to your media library as instructed. Add a list with names of people as appropriate.</p>',
			'effort'      => '30',
			'impact'      => '10',
			'weight'      => '18',
		),
		'99'  => array(
			'title'       => '<p>TESTIMONIALS - Create a Testimonials page for your website.</p>',
			'description' => '<p>A <strong>Testimonial</strong> page tells customers what other people think of your company. It\'s an opportunity to highlight your expertise and add credibility to your business.</p>',
			'howTo'       => '<p>Add a page to your site so customers can easily read what others have to say about your business. Add a page through your CMS or website as instructed.</p>',
			'effort'      => '60',
			'impact'      => '15',
			'weight'      => '19',
		),
		'100' => array(
			'title'       => '<p>Add the ability for visitors to your site to Like, Tweet, and +1 various pages via Facebook, Twitter and Google+.</p>',
			'description' => '<p>Adding sharing buttons for social networks throughout your website encourages people to connect with your business. When people connect and share your site they are endorsing you to their community. This endorsement also serves as a signal to search engines to the relevancy of the page endorsed and the site as a whole. </p>',
			'howTo'       => '<p><strong>How to add a Facebook Like icon to your homepage:</strong> 
<a href="http://developers.facebook.com/docs/plugins/">Click here</a> to add a Facebook Like button to your homepage.</p>

<p><strong>How to add a Tweet icon to your homepage:</strong>
To add a Tweet button to your homepage, follow these <a href="https://twitter.com/goodies/tweetbutton"> directions</a>. An advanced option is to create a custom Tweet button with these helpful tips provided by <a href="https://dev.twitter.com/docs/tweet-button">Twitter</a> developers.</p>

<p><strong>How to add a Google +1 icon to your homepage:</strong> 
Add the <a href="http://www.google.com/support/+/bin/static.py?hl=en&amp;page=guide.cs&amp;guide=1207011&amp;answer=1047397&amp;rd=1">Google +1 button</a>. Directions for adding the Google icon are located <a href="http://www.google.com/webmasters/add.html">here</a>. Advanced users can learn how to make a <a href="http://toolbar.google.com/buttons/apis/howto_guide.html">custom Google+ button</a>.</p>',
			'effort'      => '30',
			'impact'      => '15',
			'weight'      => '2',
		),
		'101' => array(
			'title'       => '<p>TESTIMONIAL - On your Testimonials page, add testimonials from your customers.</p>',
			'description' => '<p>A <strong>Testimonial</strong> page tells customers what others think of your company. It\'s an opportunity to highlight your expertise and add credibility to your business.</p>',
			'howTo'       => '<p>Determine what testimonials are relevant to your business and update these regularly. Be sure to get customer\'s permission to publicly share these on your website.</p>',
			'effort'      => '60',
			'impact'      => '15',
			'weight'      => '20',
		),
		'102' => array(
			'title'       => '<p>TESTIMONIALS - Mark-up your testimonials with hReview.</p>',
			'description' => '<p>hReview is the markup language, placed around your reviews, in the code of your site. Reviews coded with an hReview format immediately tells the engine that this is a \'review\'. This is important because reviews are heavily favored in search rankings. Be sure that all reviews/testimonials are tagged in hReview format.</p>',
			'howTo'       => '<p>For more information on how to install hRivew code, check the Google Webmaster Guidelines <a href="http://support.google.com/webmasters/bin/answer.py?hl=en&amp;answer=146645">here</a>.</p>',
			'effort'      => '30',
			'impact'      => '5',
			'weight'      => '21',
		),
		'103' => array(
			'title'       => '<p>Make sure you have unique product descriptions from the merchant that your site promotes.</p>',
			'description' => '<p>Search engines seek unique content, duplicate content is filtered or penalized. Search engines almost always favor the merchant content (or original author content) over yours. This puts an affiliate site or reseller at a disadvantage. It is important to revise the product descriptions and related content for the search engines to consider your products unique.</p>',
			'howTo'       => '<p>Take the merchant\'s product description and look for opportunities to improve the wording by beefing up the content. This will help improve the sales effectiveness and make the product descriptions more search friendly.</p>

<p>For example, a marketer may describe a blouse as \'indigo\' but people will more widely search for \'purple\'. This increases the probability that engines will treat the content as unique and may rank the content higher in the search results. </p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '22',
		),
		'104' => array(
			'title'       => '<p>Ensure your product descriptions are unique from the manufacturer and you use the formal product name.</p>',
			'description' => '<p>Search engines seek unique content, duplicate content is filtered or penalized. Search engines almost always favor the merchant content (or original author content) over yours. This puts an e-commerce site at a disadvantage. It is important to revise the product descriptions and related content for the search engines to consider your products unique.</p>',
			'howTo'       => '<p>Take the merchant\'s product description and look for opportunities to improve the wording of the description by beefing up the content. This will help improve the sales effectiveness and make the product descriptions more search friendly.</p>

<p>For example, a marketer may describe a blouse as &quot;indigo&quot; but people will more widely search for &quot;purple.&quot; This increases the probability that engines will treat the content as unique and may rank the content higher in the search results. </p>',
			'effort'      => '120',
			'impact'      => '10',
			'weight'      => '23',
		),
		'105' => array(
			'title'       => '<p>Add site-wide authorship mark-up.</p>',
			'description' => '<p>Google <a href="http://googlewebmastercentral.blogspot.com/2011/06/authorship-markup-and-web-search.html">started allowing</a> bloggers, writers, and journalists to claim their online content via authorship mark up in June 2011. This markup helps both authors and readers. The former ones can use it as a sort of &quot;online signature&quot; for the content that they have created, and the latter ones can easily recognize the author of particular content on a web page directly from the search results without having to click through to the page. If the mark up is set up correctly a picture of the author, together with a link to their Google+ profile should appear next to their corresponding search result in the search engine results page.</p>',
			'howTo'       => '<p>Setting up authorship markup for a whole website is very easy and could be completed in two steps:</p>

<p>Step 1: Add a link from the &quot;Contributor to&quot; section of your Google+ personal profile to your website.</p>

<p>Step 2: Add a link to your Google+ personal profile from the head section of your website. This will mean that you claim authorship rights over each page of your website.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '24',
		),
		'106' => array(
			'title'       => '<p>Confirm that your website has a sitemap.</p>',
			'description' => '<p>A sitemap is a hierarchical list of pages of a web site. It is a navigational aid that displays the web site structure with links to major sections and sub-sections. This helps visitors and search engine crawlers find pages on the site.</p>',
			'howTo'       => '<p>The WordPress plugin, &lt;a href=&quot;http://wordpress.org/extend/plugins/ps-auto-sitemap&quot;&gt;PS Auto Sitemap&lt;/a&gt;, automatically generates a site map page for your WordPress site. Please follow these steps:&lt;br/&gt;&lt;br/&gt;</p>

<p>&lt;li&gt; 1. Download PS Auto Sitemap plugin.</p>

<p>&lt;li&gt; 2. Unzip the downloaded package and upload into your WordPress plugins directory. If you use WordPress 2.7 or later, you can install from admin page.</p>

<p>&lt;li&gt; 3. Go to the plugins list and activate &quot;PS Auto Sitemap.&quot;</p>

<p>&lt;li&gt; 4. Post a page that will use as the sitemap page.</p>

<p>&lt;li&gt; 5. Insert code in the content area. Use HTML mode.</p>

<p>&lt;li&gt; 6. Define the sitemap\'s ID at &quot;PostID of the sitemap&quot; field of the settings.&lt;br/&gt;&lt;br/&gt;</p>

<p>As a quick note, PS Auto Sitemap requires: WordPress 2.3.1 or higher, compatible up to: 3.3.1.</p>',
			'effort'      => '60',
			'impact'      => '25',
			'weight'      => '3',
		),
		'107' => array(
			'title'       => '<p>Create and submit an XML  Sitemap to Google and Bing.</p>',
			'description' => '<p>A Google Sitemap is an XML file with a list of all of the pages on your website. Creating and submitting a Sitemap helps make sure that the engines know about all the pages on your site, including URLs that may not be discoverable by normal crawling processes. </p>',
			'howTo'       => '<p>In your WordPress administration section, install the plugin called &quot;Google XML Sitemaps.&quot; Activate the plugin, and then navigate to &quot;Settings&quot; and then &quot;XML-Sitemap.&quot; Click &quot;Build Sitemap.&quot; You can also adjust any setting you prefer.</p>',
			'effort'      => '60',
			'impact'      => '25',
			'weight'      => '4',
		),
		'108' => array(
			'title'       => '<p>BLOG - Create a Blog page for your website.</p>',
			'description' => '<p>Blogs have become increasingly important to communicate and engage with customers and have become extremely important platforms to improve your relevancy and thus exposure on search engines. Unique and relevant blog articles are valuable to garner links, likes, follows, etc. from targeted customers. Customers can learn more about your company, team, and products by reading your blog. We will provide blog ideas and optimization best practices in future courses.</p>',
			'howTo'       => '<p>Many website platforms or CMS systems such as <a href="http://www.wordpress.com">WordPress</a> or <a href="http://www.drupal.com">Drupal</a> make it easy to add a blog to your existing website. Work from your web dashboard and the system docs to complete.</p>

<p>If working with a custom built site or \'non-blogging\' platform, it\'s easy to attach a blog to the website. Create a sub-directory folder on your domain
(www.mydomain.com/blog) and upload as <a href="http://codex.wordpress.org/Integrating_WordPress_with_Your_Website">instructed</a>
by WordPress or other blogging platform.</p>',
			'effort'      => '15',
			'impact'      => '50',
			'weight'      => '5',
		),
		'109' => array(
			'title'       => '<p>BLOG - Add &quot;Like&quot;, &quot;Tweet&quot;, &quot;Share&quot;, and &quot;+1&quot; buttons to your blog posts.</p>',
			'description' => '<p>Make it easy for people to share your content on their social media platform of choice. Place social media icons at the bottom of every post and web page.</p>',
			'howTo'       => '<p>One of the easiest ways to add social media buttons to your site is with the free sharing tool, <a href="http://sharethis.com/publishers/get-sharing-tools">Share This</a>.</p>

<ul>
<li>Select your web platform</li>
<li>Select the style of your buttons/bars/features</li>
<li>Customize where the buttons appear</li>
</ul>',
			'effort'      => '15',
			'impact'      => '25',
			'weight'      => '6',
		),
		'110' => array(
			'title'       => '<p>BLOG - Add buttons for social bookmarking sites to your blog posts.</p>',
			'description' => '<p>Adding social media buttons to your content connects your business to the social network and allows your message to potentially reach thousands of people across the web. Using these social channels as a marketing tool will undoubtedly benefit your business and increase its online visibility exponentially.</p>

<p>Social bookmarking is a way to store and organize different web pages for a user\'s future reference. Popular social bookmarking sites include <a href="http://www.StumbleUpon.com">StumbleUpon</a>, <a href="del.icio.us">del.icio.us</a>, and <a href="digg.com">Digg</a> among others. </p>

<p>Adding these icons to your blog allows readers to \'bookmark\' your blog, which is shown to their networks on the bookmarking site.</p>',
			'howTo'       => '<ul>
<li><p>Feel free to try some social apps that gives business owners an advantage in the social landscape and search. Search online for icons to add to your site. Many designers build custom sets of icons that you can choose from. </p></li>
<li><p>To Add a Facebook Like Icon: Go to the <a href="https://developers.facebook.com/docs/plugins/">Social Plugins for Facebook Developers page</a>. Click on the Like Button section.</p></li>
<li><p>To Add a Twitter Tweet Icon: Go to <a href="http://www.twitter.com">Twitter.com</a> and click on the About section on the left-hand side of the page. Then click  on Buttons.</p></li>
<li><p>To add a Google +1 Icon: Go to <a href="https://developers.google.com/+/web/+1button/">Google Developers</a> and edit the parameters to generate your +1 button.</p></li>
<li><p>To add a Social Media Share Button to your Blog: Go to <a href="http://www.sharethis.com/get-sharing-tools/">ShareThis</a>. Select the content platform where you want to apply your buttons. Follow the instructions laid out on the next screen.</p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '15',
			'weight'      => '7',
		),
		'111' => array(
			'title'       => '<p>BLOG - Set up an email subscription option for your blog.</p>',
			'description' => '<p>Provide your blog content to customers in the way they want to receive it. RSS comes to them via a feed burner and an email subscription comes directly to their Inbox. Set up an email subscription service so people can easily sign up and get your content with your designated email preferences.</p>',
			'howTo'       => '<p>Many email providers provide subscriptions services for your blog. This allows you to strategically plan what content your customers receive and when they receive
it. <a href="http://mailchimp.com/features/rss-to-email/">MailChimp</a> is a popular email option and directions for installing the subscription service can be found <a href="http://mailchimp.com/features/rss-to-email/">here.</a></p>',
			'effort'      => '15',
			'impact'      => '15',
			'weight'      => '8',
		),
		'112' => array(
			'title'       => '<p>Mark-up your business name, address and phone number with hCard microformats.</p>',
			'description' => '<p>An hcard is a clever use of HTML that makes your contact information still look the same to your website visitors but is much easier for engines to crawl and use. Many experts believe that using an hcard on your site dramatically increases the chances of displaying a map of your location, or Maps-Plus Box, included with your Google natural listing. See an example of a Maps-Plus Box <a href="http://www.evisibility.com/blog/google-map-plus-box-is-all-over-the-place/">here</a>.</p>',
			'howTo'       => '<p>To create an hcard for you website, go to this <a href="http://microformats.org/code/hcard/creator">site</a>. You can then add this code into the footer of your site and/or on your contact page.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '8',
		),
		'113' => array(
			'title'       => '<p>BLOG - Create links out to other bloggers in your content.</p>',
			'description' => '<p>Acknowledging other authors/blogs by linking out to their content is an important part of blogging etiquette as well as an inbound marketing tactic. Acknowledging you like their content  via a \'Blogroll\' on your site tells you visitors and them you are following. The more you engage with other bloggers, the more likely they are to engage and link to you. Inbound links continually increases exposure and authority to search engines.</p>',
			'howTo'       => '<p>If your site uses a blogroll, log-in to your WordPress administration section and navigate to &quot;Links.&quot; Then, click &quot;Add New.&quot; Simply add the blog name and URL, and then click &quot;Add Link.&quot; </p>

<p>If your theme doesn\'t currently display a blogroll, you should be able to add it by navigating to &quot;Appearance,&quot; then clicking &quot;Widgets,&quot; and then dragging &quot;Links&quot; into an active sidebar block.</p>',
			'effort'      => '15',
			'impact'      => '2',
			'weight'      => '9',
		),
		'114' => array(
			'title'       => '<p>Create KML file for your location and upload it on your site.</p>',
			'description' => '<p>Keyhole Markup Language (KML for short) is a file format used to display geographic data in an Earth browser. Google Earth was the first program able to view and graphically edit KML files. In a KML file one could specify different geo-location features such as place marks, images, polygons, 3D models, textual descriptions, etc. Currently Google Earth, Google Maps, and Google Maps for mobile support and recognize KML format files.</p>',
			'howTo'       => '<ul>
<li>Click <a href="http://www.geositemapgenerator.com/input">here</a> and fill in your business information.</li>
<li>At the next step, fill in your domain name. The default location where your KML file will appear is www.yourdomain.com/locations.kml.</li>
<li>Click &quot;Generate&quot;</li>
<li>Go to the root directory of your website and upload the KML file there.</li>
</ul>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '9',
		),
	),
	'100' => array(
		'115' => array(
			'title'       => '<p>Join your local <a href="http://www.bbb.org">Better Business Bureau</a>.</p>',
			'description' => '<p>Your local <a href="http://www.bbb.org">Better Business Bureau</a> will often provide a link to your business website if you are a BBB member. Accreditation is given to businesses that meet BBB standards and have passed their application.  </p>',
			'howTo'       => '<p>From the <a href="http://www.bbb.org/us/bbb-accreditation-application">BBB Accreditation Application</a>, you will find more information about their specific resources and support services. You will need to contact your local Better Business Bureau location to get started becoming a member. </p>',
			'effort'      => '15',
			'impact'      => '50',
			'weight'      => '1',
		),
		'116' => array(
			'title'       => '<p>Ask for a link from the the building or development your business is located in.</p>',
			'description' => '<p>Many malls, business buildings, and business parks have websites where they list all the companies that have premises there. They usually only mention the businesses, but sometimes if appropriate and relevant, they could add links to the websites of the businesses.</p>',
			'howTo'       => '<p>Reach out to the management of the building/mall that your business is located in. Ask them to add a link from their website to your website.</p>',
			'effort'      => '30',
			'impact'      => '10',
			'weight'      => '11',
		),
		'117' => array(
			'title'       => '<p>Sponsor a local youth sports teams and have the league website provide a link back to your website.</p>',
			'description' => '<p>Sponsoring a local sports team is a great way to support your local community and provide some exposure for your business.In addition to doing something great for the community these sponsorship opportunities can also benefit your website and online presence. If the team or league has a sponsorship page on their website they would probably be happy to provide a link to your website.</p>',
			'howTo'       => '<p>Reach out to a representative from your local league to learn more about sponsorship opportunities. Be sure to ask if they have a website and if they offer the opportunity to include a logo and link from the website league to your business.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '12',
		),
		'118' => array(
			'title'       => '<p>Sponsor a local animal shelter and potentially provide a link back to your site.</p>',
			'description' => '<p>Sponsoring an animal shelter or other local service is a great way to support your local community. In addition to doing something great for the community, these sponsorship opportunities can also benefit your website and online presence. If the shelter or organization has a sponsorship page on their website, they would probably be happy to provide a link to your website.</p>',
			'howTo'       => '<p>Reach out to a representative from your local shelter to learn more about sponsorship opportunities. Be sure to ask if they have a website and if they can include a logo and link from their website to yours.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '14',
		),
		'119' => array(
			'title'       => '<p>If you hired a photographer to take photographs of you, your office, wedding, party, or other occasion, ask them to post some pictures on their site and provide a link back to your site.</p>',
			'description' => '<p>People you hire can be a great source of potential links. If you use a photographer for any purpose, encourage them to use the photographs on their website and see if they would be willing to link to your website.</p>',
			'howTo'       => '<p>Reach out to the photographer and see if they would be willing to post your photographs and provide a link to your website.</p>',
			'effort'      => '30',
			'impact'      => '5',
			'weight'      => '15',
		),
		'120' => array(
			'title'       => '<p>If your business has traveled using a local travel agency,  offer them a testimonial or endorsement for their website.</p>',
			'description' => '<p>People you hire can be a great source of potential links. If you have used a travel agency for any purpose, see if they would like a testimonial or endorsement on their website and see if they would be willing to link to your website.</p>',
			'howTo'       => '<p>Reach out to the travel agent and see if they would be willing to post your testimonial or endorsement on their website and provide a link to your website.</p>',
			'effort'      => '30',
			'impact'      => '5',
			'weight'      => '16',
		),
		'121' => array(
			'title'       => '<p>If you moved office locations and used a moving company, offer them a testimonial or endorsement for their website.</p>',
			'description' => '<p>People you hire can be a great source of potential links. If you have used a moving company, see if they would like a testimonial or endorsement on their website and see if they would be willing to link to your website.</p>',
			'howTo'       => '<p>Reach out to the moving company and see if they would be willing to post your testimonial or endorsement on their website and provide a link to your website.</p>',
			'effort'      => '30',
			'impact'      => '5',
			'weight'      => '17',
		),
		'122' => array(
			'title'       => '<p>If you bought or rented a business vehicle,  offer them a testimonial or endorsement for their website.</p>',
			'description' => '<p>People you hire or from whom you bought products and services can be a great source of potential links. Another great option would be if you\'ve purchased or rented a business vehicle or company equipment. If one of these options apply to you, see if they would be willing to post your testimonial or endorsement on their website and provide a link to your website.</p>',
			'howTo'       => '<p>Reach out to the auto dealer or business. Ask if they would be willing to post your testimonial or endorsement on their website and provide a link to your website.</p>',
			'effort'      => '30',
			'impact'      => '5',
			'weight'      => '18',
		),
		'123' => array(
			'title'       => '<p>Sponsor a student club at your local high school or university.</p>',
			'description' => '<p>Sponsoring a student club is a great way to support your local community and provide some positive exposure for your business. In addition, these sponsorship opportunities can also benefit your website\'s exposure and online presence as well, so see if the student club has a sponsorship page that might be able to provide a link to your website.</p>',
			'howTo'       => '<p>Reach out to the local high school and other schools in your community to learn more about sponsorship opportunities. Be sure to ask if they have a website and if they can include a logo and link from their website to yours.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '19',
		),
		'124' => array(
			'title'       => '<p>Join your local <a href="http://www.chamberofcommerce.com/chambers">Chamber of Commerce</a>.</p>',
			'description' => '<p>Your local <a href="http://www.chamberofcommerce.com/chambers">Chamber of Commerce</a> will often provide a link to your website in their membership directory if you are a member. In addition, a local Chamber of Commerce membership is a great resource for small business owners. Accreditation will give you access to a large network of people in your same industry.</p>',
			'howTo'       => '<p>From the <a href="http://www.chamberofcommerce.com/chambers">Chamber of Commerce</a>, you will find more information about their resources and support services.</p>',
			'effort'      => '15',
			'impact'      => '25',
			'weight'      => '2',
		),
		'125' => array(
			'title'       => '<p>Research and get a link for your site from local blogs and directories.</p>',
			'description' => '<p>Local blogs and directories are great targets for link building opportunities.  Receiving these local links will typically help your standing in Google Maps, as well as strengthen your overall site\'s authority.</p>',
			'howTo'       => '<p>The best approach to finding these local blog and directory sites is to search for the following phrases:</p>

<ul>
<li>&quot;(your city or surrounding cities) directory&quot;</li>
<li>&quot;(your city or surrounding cities) business directory&quot;</li>
<li>&quot;(your city or surrounding cities) blogs&quot;</li>
<li>&quot;(your neighborhood) directory&quot;</li>
<li>&quot;(your neighborhood) blogs&quot;</li>
</ul>

<p>After you find a few directories and blogs that are ranked highly in the search results, submit your site and/or work to get a link back to your site from these resources.</p>',
			'effort'      => '15',
			'impact'      => '30',
			'weight'      => '3',
		),
		'126' => array(
			'title'       => '<p>Submit your site to a local college or high school website.</p>',
			'description' => '<p>Local colleges and universities are great opportunities for link building purposes. It is known that websites with .edu extensions (like high schools and colleges) generally have much more link strength than their .com counterparts.</p>',
			'howTo'       => '<p>Local colleges and universities great sites to get links, but getting these types of links can be difficult. The most effective strategy is to provide a student discount to your products or services (if applicable to the high school/college student). </p>

<p>Schools love to offer discounts to its students and would likely be willing to link to your site if your website provides more information regarding the discount or perhaps offers an option to download a coupon or promotion</p>',
			'effort'      => '15',
			'impact'      => '20',
			'weight'      => '4',
		),
		'127' => array(
			'title'       => '<p>Submit your site to a local newspaper.</p>',
			'description' => '<p>Your local newspaper is almost always a trusted source that the search engines will value as it relates to local content. Getting a link from a nearby newspaper site could be possible depending on the various services and tools that your local paper provides.</p>',
			'howTo'       => '<p>Consider the following strategies to get a link from your local newspaper:</p>

<ul>
<li><p>Search to see if the newspaper has a local business directory and submit your business if they do.</p></li>
<li><p>Continue to try and pick up local press about your business and your impact on the community around you.</p></li>
<li><p>Provide a discount or coupon for your business. You can then promote users to visit your site and redeem the coupon or promotion.</p></li>
</ul>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '5',
		),
		'128' => array(
			'title'       => '<p>Submit your site to local business resource pages.</p>',
			'description' => '<p>Getting a local site to link to your website from their own is a great way to optimize your site and elevate your presence.  Many times, local business websites and locally focused sites have a resource page that links to other area businesses. </p>',
			'howTo'       => '<p>If you know of some popular local businesses, check their websites and see if they have a local resources page. If they do, reach out or send them a polite request to add your website. </p>

<p>For other ideas,  search for specific relevant industries in your specific city or geographic location using a search engine.  Once you find one that relates to your business and industry, search for the business\'s resource page.  If they have a resource page, contact them and ask to be included.</p>',
			'effort'      => '60',
			'impact'      => '20',
			'weight'      => '6',
		),
		'129' => array(
			'title'       => '<p>Interview local businesses or local bloggers on your site.</p>',
			'description' => '<p>Interviewing local businesses or local bloggers falls into the category &quot;ego bait.&quot; The purpose of doing this is to have others, already thought leaders in your niche, promote content on your website. The easiest way to make them do this is by writing about them. </p>

<p>Interviewing them provides your business more relevance (it is their own answers, and it is them talking about themselves), more engagement (they will definitely know and expect that you will be posting an interview with them), and more high quality content for your blog.</p>',
			'howTo'       => '<ul>
<li><p>Discover who the thought leaders in your niche are, and find their contact information. </p></li>
<li><p>If you haven\'t had the chance to talk with them previously, it is preferable that you reach them via email. </p></li>
<li><p>If you have previously interacted with them, it might be more convenient to directly give them a call. </p></li>
<li><p>Ask if would like to answer a few questions in written form. </p></li>
<li><p>If they agree, send them an email with questions such as, &quot;What motivates you to keep blogging about [your industry]?,&quot; &quot;How long have you been involved in [your industry]&quot;, and &quot;What would you advise our readers about [FAQ from your industry]&quot;, etc.</p></li>
</ul>',
			'effort'      => '120',
			'impact'      => '10',
			'weight'      => '7',
		),
		'130' => array(
			'title'       => '<p>Give an interview for a local radio station, TV station, or newspaper.</p>',
			'description' => '<p>Exposure in local media often has a very positive effect on your small business. Local newspapers, radio and TV stations are often read or listened to and watched predominantly by locals, so their audience is targeted. People are more likely to purchase local products and services, so there couldn\'t be a better opportunity than getting some additional exposure in any of the mentioned channels. Being interviewed by a local media reporter can help you by (1) positioning yourself as a specialist in your area of expertise, or (2) gaining exposure and thus potential customers.</p>',
			'howTo'       => '<p>The most common approach in this case would be the proactive one. There are some newspapers that have a &quot;guest reporter&quot; option or are looking for a specialist in a particular industry to be interviewed section, but these are rather rare especially in digital media. You may have to reach out to a a particular reporter who has a history of writing pieces on local news, especially covering stories for small businesses. Write him or her a short paragraph about your business story, and mention why you believe your story would be interesting to their readers.</p>',
			'effort'      => '120',
			'impact'      => '20',
			'weight'      => '8',
		),
		'131' => array(
			'title'       => '<p>Sponsor a local meeting or MeetUp group that is relevant to your industry.</p>',
			'description' => '<p>Local meeting groups usually have either a website, or they organize themselves via some sort of event organization platform. For all the group members to be able to find the place, they usually need to give some directions such as &quot;Bob\'s Cafe at 123 Abc Street&quot;. Search engines often consider this a strong citation for the business, which can help your local listing achieve higher rankings in the search results.</p>',
			'howTo'       => '<p>It is not necessary that your sponsorship has to be in the form of money. You can offer a location or space for the group meeting for free. You can also offer free or discounted products or services to members of a particular group. It is best if the group is relevant to your industry, but it is not necessary.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '9',
		),
	),
	'101' => array(
		'132' => array(
			'title'       => '<p>Create a mobile version of your website.</p>',
			'description' => '<p>Most websites are built to be seen through a computer monitor and thus the website design works well for its visitors. Unfortunately, with the tremendous growth of smartphone use, more and more people are looking at ites through their mobile phones, and the website usability is lowered for these visitors. </p>

<p>You can easily set up a mobile friendly version of your business website so that mobile visitors get a great user experience. Both your mobile and computer users should enjoy viewing your website.</p>',
			'howTo'       => '<p>We recommend a service offered by <a href="http://www.dudamobile.com/">DudaMobile</a> to help your website with mobile. It\'s easy to get started, and it offers both a free plan and a paid plan for as low as $9 per month. If you sign up with DudaMobile, please let us know about your experience in our Q&amp;A section.</p>',
			'effort'      => '120',
			'impact'      => '50',
			'weight'      => '1',
		),
	),
	'103' => array(
		'133' => array(
			'title'       => '<p>Submit your blog to <a href="http://www.Technorati.com">Technorati</a>.</p>',
			'description' => '<p><a href="http://www.Technorati.com">Technorati</a> is a user generated web directory for blogs and media. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to your business. </p>',
			'howTo'       => '<p>You will need to create an account and fill out the requested information to <a href="http://technorati.com/account/signup/">submit your business</a> to Technorati. Follow the directions carefully to complete your submission. It is important that you complete the profile information about your business to the greatest extent possible.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '1',
		),
		'134' => array(
			'title'       => '<p>Submit your site to <a href="http://www.galaxy.com/">Galaxy.com</a>.</p>',
			'description' => '<p><a href="http://www.galaxy.com/">Galaxy.com</a> is a web directory for small businesses. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to a website. A Galaxy.com editor will manually review your submitted site.</p>',
			'howTo'       => '<p>From the <a href="http://www.galaxy.com/">Galaxy.com homepage</a>, click on the <a href="https://galaxy.logika.net/submit?d=">Submit a Site</a> link, which is located in the upper right-hand corner. Choose your payment option. Then select the most relevant directory specifications for your site. Follow the directions to complete your submission. Prices start at $9.95 for a one-time fee.</p>',
			'effort'      => '15',
			'impact'      => '2',
			'weight'      => '13',
		),
		'135' => array(
			'title'       => '<p>Submit your small business site to <a href="http://www.incrawler.com">InCrawler</a>.</p>',
			'description' => '<p><a href="http://www.incrawler.com">InCrawler</a> is a web directory for small businesses. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to a website.</p>',
			'howTo'       => '<p>From the <a href="http://www.incrawler.com">InCrawler homepage</a>, navigate to the most relevant and specific category for your site. Then, click the link for your chosen category and select &quot;Add URL.&quot; For more information, view <a href="http://www.incrawler.com/cgi-bin/dir/addurl.cgi">this link</a>. A basic listing will be reviewed within a week\'s time.</p>',
			'effort'      => '15',
			'impact'      => '2',
			'weight'      => '14',
		),
		'136' => array(
			'title'       => '<p>Submit your site to <a href="http://goguides.org/">GoGuides.org</a>.</p>',
			'description' => '<p><a href="http://goguides.org/">GoGuides.org</a> is a web directory of sites for you to submit your business. Inclusion in this directory costs a one time fee of $69.95 via credit card or PayPal. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to a website.</p>',
			'howTo'       => '<p>Add your business url to the <a href="http://goguides.org/">GoGuides Directory</a> by clicking <a href="http://www.goguides.org/addurl.html">here</a>.</p>

<p>Follow the directions carefully to complete your submission. It is important that you complete the profile information about your business to the greatest extent possible. Be sure to include your targeted keywords in the &quot;Tags&quot; area of the submission form. </p>

<p>Go to <a href="http://www.goguides.org/info/addurl.htm">this link</a> for more information on submitting your site to GoGuides.org.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '15',
		),
		'137' => array(
			'title'       => '<p>Submit your site to <a href="http://joeant.com/">JoeAnt.com</a>.</p>',
			'description' => '<p><a href="http://joeant.com/">JoeAnt.com</a> is a web directory of sites for you to submit your business. Inclusion in this volunteer-edited directory costs a one time fee of $39.99. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to a website.</p>',
			'howTo'       => '<p>Add your business to <a href="http://joeant.com/">JoeAnt.com web directory</a> by clicking on the topic and the sub-category that best fits your business. Then, click to add your website at the top of the page. </p>

<p><a href="http://joeant.com/">Read the Suggest a Site</a> directions for more information. Be sure to read the site\'s guidelines before agreeing to submit your site. </p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '16',
		),
		'138' => array(
			'title'       => '<p>Submit your site to <a href="http://gimpsy.com/">Gimpsy.com</a>.</p>',
			'description' => '<p><a href="http://gimpsy.com/">Gimpsy.com</a> is a web directory of sites for you to submit your business. It categorizes a site based on the services that it provides versus its subject matter. This directory has three one-time <a href="http://www.gimpsy.com/gimpsy/searcher/suggest_compare.php">fee options</a>: free, $29, and $49.</p>

<p>Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to a website.</p>',
			'howTo'       => '<p>To add your business to <a href="http://gimpsy.com/">Gimpsy.com</a>, you will first need to fill out the <a href="http://www.gimpsy.com/gimpsy/searcher/register.php?rand=3845&amp;PHPSESSID=3e103279e90a4f6dd7f34b7c8c1067d0">registration form</a>. After filling out the form, Gimpsy will email you a confirmation that includes a log-in password and instructions for next steps to <a href="http://www.gimpsy.com/gimpsy/searcher/suggest.php?cid=2549">submit your site for an editor to review</a>. </p>

<p><a href="http://www.gimpsy.com/gimpsy/doc/faq/faq_main.php">Click here</a> to learn more information about the Gimpsy directory. It is important that you review and complete the profile information about your business to the greatest extent possible. </p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '17',
		),
		'139' => array(
			'title'       => '<p>Submit your site to <a href="http://dir.yahoo.com/">Yahoo! Directory</a>.</p>',
			'description' => '<p>The <a href="https://ecom.yahoo.com/dir/submit/intro">Yahoo! Directory</a> is a quality web directory that costs $299 per year for inclusion.</p>',
			'howTo'       => '<p>Go to the <a href="https://ecom.yahoo.com/dir/submit/intro/">Submit a site</a> link under Yahoo! Directory listings and click <a href="https://ecom.yahoo.com/dir/submit/terms/">Get Started</a>. Follow the four-step process to complete your submission on Yahoo!.</p>',
			'effort'      => '15',
			'impact'      => '15',
			'weight'      => '18',
		),
		'140' => array(
			'title'       => '<p>Sign up to be in <a href="http://www.kqzyfj.com/click-3864201-10708305">Business.com</a>.</p>',
			'description' => '<p><a href="http://www.kqzyfj.com/click-3864201-10708305">Business.com</a> is a trusted web directory that serves about 40 million unique business users. Inclusion in the Business.com directory costs $299 per year but is now updated and available to our members for a special promotion of $199.</p>',
			'howTo'       => '<p>To get a special promotional price of $199 for your Business.com directory listing, go to <a href="https://origin.business.com/purchase/directoryListing/">this link</a>. Follow the steps to complete your submission, and be sure to enter the promo code <strong>DK100</strong> to get your discount.</p>

<p>If you currently have an existing account, you can log into your account and still use the code for any new listings created. </p>

<p>Please note: the listings are automatically set up for annual auto-renewal at the $299 price. To remove the auto-renewal or if you are having any issues with your account, please email customerservice@business.com or samantha.foertsch@business.com. </p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '19',
		),
		'141' => array(
			'title'       => '<p>Submit your blog to <a href="http://www.BlogCatalog.com">BlogCatalog</a>.</p>',
			'description' => '<p><a href="http://www.BlogCatalog.com">BlogCatalog.com</a> is a quality web directory for blogs. Blog submissions can take up to 48 hours to be reviewed by the editors. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to your business. </p>',
			'howTo'       => '<p>You will need to submit your blog and create an account <a href="http://www.blogcatalog.com/login?goto=%2Faccount%2Flisting_fees">here</a>. Follow the directions carefully to complete your registration. To learn more details about BlogCatalog,  <a href="http://www.blogcatalog.com/help">click here</a>.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '2',
		),
		'142' => array(
			'title'       => '<p>Sign up to be in the <a href="http://botw.org?uuid=44833">Best of the Web</a> Directory.</p>',
			'description' => '<p>The <a href="http://botw.org?uid=44833">Best of the Web</a> Directory is a high quality editor-reviewed web directory that generally costs $149.95 annually or $299.95 one-time. However, our members can get a 20% discount by using the coupon code <strong>DIYSAVE20</strong>. Editors from Best of the Web manually review websites and approve websites within three business days.  </p>',
			'howTo'       => '<p>Click &quot;Sign Up Now&quot; on the Best of the Web <a href="http://botw.org/helpcenter/submitcommercial.aspx?uid=44833">Sign Up page</a>. At the bottom of Step 2, enter <strong>DIYSAVE20</strong> to receive $30 off the annual program and $60 on the lifetime program.  Follow the rest of the steps requested to complete your Best of the Web submission. </p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '20',
		),
		'143' => array(
			'title'       => '<p>Submit your site to <a href="http://www.directoryworld.net">DirectoryWorld</a>.</p>',
			'description' => '<p><a href="http://www.directoryworld.net">DirectoryWorld</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.directoryworld.net">Click here</a> to begin listing your site on DirectoryWorld. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '21',
		),
		'144' => array(
			'title'       => '<p>Submit your site to <a href="http://www.cannylink.com/">CannyLink</a>.</p>',
			'description' => '<p><a href="http://www.cannylink.com/">CannyLink</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.cannylink.com/">Click here</a> to begin listing your site on CannyLink. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '22',
		),
		'145' => array(
			'title'       => '<p>Submit your site to <a href="http://www.qango.com/">Qango</a>.</p>',
			'description' => '<p><a href="http://www.qango.com/">Qango</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.qango.com/">Click here</a> to begin listing your site on Qango. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '23',
		),
		'146' => array(
			'title'       => '<p>Submit your site to <a href="http://www.dirmania.org/">Dirmania</a>.</p>',
			'description' => '<p><a href="http://www.dirmania.org/">Dirmania</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.dirmania.org/">Click here</a> to begin listing your site on Dirmania. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '24',
		),
		'147' => array(
			'title'       => '<p>Submit your site to <a href="http://www.ithacaforward.org/">Ithaca Forward</a>.</p>',
			'description' => '<p><a href="http://www.ithacaforward.org/">Ithaca Forward</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.ithacaforward.org/">Click here</a> to begin listing your site on Ithaca Forward. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '25',
		),
		'148' => array(
			'title'       => '<p>Submit your site to <a href="http://www.kahuki.com">Kahuki</a>.</p>',
			'description' => '<p><a href="http://www.kahuki.com">Kahuki</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.kahuki.com">Click here</a> to begin listing your site on Kahuki. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '26',
		),
		'149' => array(
			'title'       => '<p>Submit your site to <a href="http://www.wikidweb.com/">WikidWeb</a>.</p>',
			'description' => '<p><a href="http://www.wikidweb.com/">WikidWeb</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.wikidweb.com/">Click here</a> to begin listing your site on WikidWeb. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '27',
		),
		'150' => array(
			'title'       => '<p>Submit your site to <a href="http://www.bizhwy.com">BizHwy</a>.</p>',
			'description' => '<p><a href="http://www.bizhwy.com">BizHwy</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.bizhwy.com">Click here</a> to begin listing your site on BizHwy. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '29',
		),
		'151' => array(
			'title'       => '<p>Submit your blog to <a href="http://www.Blogged.com">Chime.in</a>.</p>',
			'description' => '<p><a href="http://www.Blogged.com">Chime.in</a> (formerly Blogged.com) is a quality web directory for blogs. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to your business. To learn more details about Chime.in, <a href="http://chime.in/about/about_us">click here</a>.</p>',
			'howTo'       => '<p>You will need to create an account and fill out the requested information to <a href="http://support.chime.in/entries/21654003-how-to-create-a-chime-in-account">create a Chime.in account</a>. Follow the directions carefully to complete your registration.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '3',
		),
		'152' => array(
			'title'       => '<p>Submit your site to <a href="http://hivethrive.com/">Hive Thrive</a>.</p>',
			'description' => '<p><a href="http://hivethrive.com/">Hive Thrive</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://hivethrive.com/">Click here</a> to begin listing your site on Hive Thrive. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '30',
		),
		'153' => array(
			'title'       => '<p>Submit your site to <a href="http://www.eemes.com">Eemes</a>.</p>',
			'description' => '<p><a href="http://www.eemes.com">Eemes</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.eemes.com">Click here</a> to begin listing your site on Eemes. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '31',
		),
		'154' => array(
			'title'       => '<p>Submit your site to <a href="http://addgoodsites.com/">Add Good Sites</a>.</p>',
			'description' => '<p><a href="http://addgoodsites.com/">Add Good Sites</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://addgoodsites.com/">Click here</a> to begin listing your site on Add Good Sites. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '32',
		),
		'155' => array(
			'title'       => '<p>Submit your site to <a href="http://www.open-free-directory.com/">Open Free Directory</a>.</p>',
			'description' => '<p><a href="http://www.open-free-directory.com/">Open Free Directory</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.open-free-directory.com/">Click here</a> to begin listing your site on Open Free Directory. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '33',
		),
		'156' => array(
			'title'       => '<p>Submit your site to <a href="http://onebestlink.com">One Best Link</a>.</p>',
			'description' => '<p><a href="http://onebestlink.com">One Best Link</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://onebestlink.com">Click here</a> to begin listing your site on One Best Link. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '34',
		),
		'157' => array(
			'title'       => '<p>Submit your site to <a href="http://onemission.com">One Mission</a>.</p>',
			'description' => '<p><a href="http://onemission.com">One Mission</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://onemission.com">Click here</a> to begin listing your site on One Mission. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '35',
		),
		'158' => array(
			'title'       => '<p>Submit your site to <a href="http://www.tech4on.com/">Tech4on</a>.</p>',
			'description' => '<p><a href="http://www.tech4on.com/">Tech4on</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.tech4on.com/">Click here</a> to begin listing your site on Tech4on. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '36',
		),
		'159' => array(
			'title'       => '<p>Submit your site to <a href="http://alive-directory.com/">Alive Directory</a>.</p>',
			'description' => '<p><a href="http://alive-directory.com/">Alive Directory</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://alive-directory.com/">Click here</a> to begin listing your site on Alive Directory. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '37',
		),
		'160' => array(
			'title'       => '<p>Submit your site to <a href="http://www.Littlewebdirectory.com">Litte Web Directory</a>.</p>',
			'description' => '<p><a href="http://www.Littlewebdirectory.com">Litte Web Directory</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.Littlewebdirectory.com">Click here</a> to begin listing your site on Litte Web Directory. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '38',
		),
		'161' => array(
			'title'       => '<p>Submit your site to <a href="http://www.webworldindex.com/ads.html">WebWorld Index</a>.</p>',
			'description' => '<p><a href="http://www.webworldindex.com/ads.html">WebWorld Index</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.webworldindex.com/ads.html">Click here</a> to begin listing your site on WebWorld Index. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '39',
		),
		'162' => array(
			'title'       => '<p>Submit your blog to <a href="http://www.Alltop.com">Alltop</a>.</p>',
			'description' => '<p><a href="http://www.Alltop.com">AllTop</a> is a web directory for blogs and news websites. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to your business. To learn more details about AllTop, <a href="http://alltop.com/about/">click here</a>.</p>',
			'howTo'       => '<p>You will need to create an account and fill out the requested information to <a href="http://alltop.com/submission/">submit your  business</a>. Follow the directions carefully to complete your submission. It is important that you complete the profile information about your business to the greatest extent possible.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '4',
		),
		'163' => array(
			'title'       => '<p>Submit your site to <a href="http://hotvsnot.com">HotVsNot.com</a>.</p>',
			'description' => '<p><a href="http://hotvsnot.com">HotVsNot.com</a>is a free web directory with hand-picked links chosen by editors. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to a website.</p>',
			'howTo'       => '<p><a href="http://hotvsnot.com/Add-Site/Add-Site.aspx">Click here</a> to begin listing your site on HotVsNot.com. Your website will be reviewed by editors to determine if it meets their qualifications before it\'s approved. Therefore, be sure to complete the information about your business to the greatest extent possible. You can also read more about their <a href="http://www.hotvsnot.com/Editorial_Policy">editorial policy</a> to learn more about how to get a listing. </p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '4',
		),
		'164' => array(
			'title'       => '<p>Submit your site to <a href="http://www.stpt.com/directory/addsite.htm">STPT</a>.</p>',
			'description' => '<p><a href="http://www.stpt.com/directory/addsite.htm">STPT</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.stpt.com/directory/addsite.htm">Click here</a> to begin listing your site on STPT. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '40',
		),
		'165' => array(
			'title'       => '<p>Submit your site to <a href="http://www.a3place.com">a3 Place</a>.</p>',
			'description' => '<p><a href="http://www.a3place.com">a3 Place</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.a3place.com">Click here</a> to begin listing your site on a3 Place. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '41',
		),
		'166' => array(
			'title'       => '<p>Submit your site to <a href="http://www.interesting-dir.com">Interesting Dir</a>.</p>',
			'description' => '<p><a href="http://www.interesting-dir.com">Interesting Dir</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.interesting-dir.com">Click here</a> to begin listing your site on Interesting Dir. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '42',
		),
		'167' => array(
			'title'       => '<p>Submit your site to <a href="http://www.one-sublime-directory.com">One Sublime Directory</a>.</p>',
			'description' => '<p><a href="http://www.one-sublime-directory.com">One Sublime Directory</a> is a directory that allows you to list your business. Adding your business information to this directory and linking back to your website helps you grow the relevance of your website and your overall online visibility.</p>',
			'howTo'       => '<p><a href="http://www.one-sublime-directory.com">Click here</a> to begin listing your site on One Sublime Directory. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '43',
		),
		'168' => array(
			'title'       => '<p>Submit your blog to  <a href="http://blogs.botw.org/">Best of the Web Blogs Directory</a>.</p>',
			'description' => '<p><a href="http://blogs.botw.org/">Best of the Web Blogs</a> is an editorially-reviewed quality blog directory since 2005.  Editors from Best of the Web manually review and approve the websites. The cost for inclusion starts at $149.95. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to your business. </p>',
			'howTo'       => '<p>You will need to create an account and fill out the requested information on the <a href="http://blogs.botw.org/helpcenter/submitblog.aspx">Best of the Web Getting Started Page</a>, and then proceed to process the request. Follow the directions carefully to complete your submission. It is important that you complete the profile information about your business to the greatest extent possible.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '5',
		),
		'169' => array(
			'title'       => '<p>Submit your site to <a href="http://dmegs.com">Dmegs</a>.</p>',
			'description' => '<p><a href="http://dmegs.com">Dmegs</a> is a free web directory with hand-picked links chosen by editors. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to a website.</p>',
			'howTo'       => '<p><a href="http://dmegs.com/submit_site/?id=0">Click here</a> to submit your website to the Dmegs Directory. Navigate to the most relevant category and then choose a sub-category for your website. Be sure to click the <a href="http://www.dmegs.com/submit_site/">Submit Website</a> icon afterward. We recommend purchasing the Featured Submission for $9.95 per year with lifetime search engine submission.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '7',
		),
		'170' => array(
			'title'       => '<p>Submit your blog to <a href="http://www.bloggeries.com">Bloggeries</a>.</p>',
			'description' => '<p><a href="http://www.bloggeries.com">Bloggeries</a> is a quality web directory for blogs. Bloggeries has a usable interface with straightforward categories and is a great source of permanent relevant links to blogs. The one-time-only cost for Bloggeries starts at $34.99.</p>',
			'howTo'       => '<p>Fill out the requested information on the <a href="http://www.bloggeries.com/submit.php">Bloggeries submit page</a> and proceed to process the inclusion request. Follow the directions carefully to complete your submission. </p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '7',
		),
		'171' => array(
			'title'       => '<p>Submit your blog to <a href="http://www.bloguniverse.com">BlogUniverse</a>.</p>',
			'description' => '<p><a href="http://www.bloguniverse.com">BlogUniverse</a> is an editorially-reviewed blog directory. The cost for inclusion is a $4.99 one-time-only fee for a regular listing that is reviewed between 24 and 72 hours.</p>',
			'howTo'       => '<p>From the <a href="http://www.bloguniverse.com/suggest-link.php?id=0">Suggest Link page</a>, choose your payment option and provide a title, listing URL, and brief description. Follow the directions carefully to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '8',
		),
		'172' => array(
			'title'       => '<p>Submit your small business site to <a href="http://www.smebd.org">Small Business Directory</a>.</p>',
			'description' => '<p><a href="http://www.smebd.org">SMEBD</a> is a quality web directory for small businesses. Quality web directories categorize, describe and link to useful websites. They are a great source for permanent, relevant links to a website.</p>',
			'howTo'       => '<p>From the <a href="http://www.smebd.org">Small Business Directory homepage</a>, navigate to the most relevant category for your site. Then, on that category page, click the link to suggest your website. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '2',
			'weight'      => '9',
		),
	),
	'104' => array(
		'173' => array(
			'title'       => '<p>Attempt to obtain a link from <a href="http://www.copyblogger.com/writing-rituals/">copyblogger.com</a>.</p>',
			'description' => '<p>Getting sites to link to your site is extremely important to improve the overall rankings of your site as each link counts as a \'vote\' for the content of your site. Some sites have more \'voting power\' or \'link strength\' in the eyes of the engines. We have identified a backlink one or more of your competitors are receiving and think it could be an opportunity for you to contact the site and have them link to you as well.</p>

<p>The domain <strong>copyblogger.com</strong> is currently linked to 2 of your competitors listed below and has a link strength (on a scale of 1-10) of 5:</p>

<ul>
<li>thunderseo.com:
<ul><li><a href="http://www.copyblogger.com/sex-and-the-city-blogging/">http://www.copyblogger.com/sex-and-the-city-blogging/</a></li></ul></li>
<li>digitaloperative.com:
<ul><li><a href="http://www.copyblogger.com/writing-rituals/">http://www.copyblogger.com/writing-rituals/</a></li></ul></li>
</ul>',
			'howTo'       => '<p>Go to copyblogger.com and search for a contact form or general email address to send a note.</p>

<p>Ask for a link from the site owner or web master at copyblogger.com, who is linking to your competitors. <a href="mailto:enter@email.address?body=Hello, I am the owner of YOURDOMAIN.com. I noticed your web page http://www.copyblogger.com/writing-rituals/ is linking to some resources that are similar to my site. I was wondering if you would consider adding a link to my site YOURDOMAIN.com if you think your visitors would find it useful.">CLICK HERE FOR AN E-MAIL SHORTCUT.</a></p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '1',
		),
		'174' => array(
			'title'       => '<p>Attempt to obtain a link from <a href="http://www.searchengineworkshops.com/links.html">searchengineworkshops.com</a>.</p>',
			'description' => '<p>Getting sites to link to your site is extremely important to improve the overall rankings of your site as each link counts as a \'vote\' for the content of your site. Some sites have more \'voting power\' or \'link strength\' in the eyes of the engines. We have identified a backlink one or more of your competitors are receiving and think it could be an opportunity for you to contact the site and have them link to you as well.</p>

<p>The domain <strong>searchengineworkshops.com</strong> is currently linked to 1 of your competitors listed below and has a link strength (on a scale of 1-10) of 5:</p>

<ul>
<li>titan-seo.com:
<ul><li><a href="http://www.searchengineworkshops.com/links.html">http://www.searchengineworkshops.com/links.html</a></li></ul></li>
</ul>',
			'howTo'       => '<p>Go to searchengineworkshops.com and search for a contact form or general email address to send a note.</p>

<p>Ask for a link from the site owner or web master at searchengineworkshops.com, who is linking to your competitors. <a href="mailto:enter@email.address?body=Hello, I am the owner of YOURDOMAIN.com. I noticed your web page http://www.searchengineworkshops.com/links.html is linking to some resources that are similar to my site. I was wondering if you would consider adding a link to my site YOURDOMAIN.com if you think your visitors would find it useful.">CLICK HERE FOR AN E-MAIL SHORTCUT.</a></p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '1',
		),
		'175' => array(
			'title'       => '<p>Attempt to obtain a link from <a href="http://outspokenmedia.com/seo/seo-trademark-application-terminated/">outspokenmedia.com</a>.</p>',
			'description' => '<p>Getting sites to link to your site is extremely important to improve the overall rankings of your site as each link counts as a \'vote\' for the content of your site. Some sites have more \'voting power\' or \'link strength\' in the eyes of the engines. We have identified a backlink one or more of your competitors are receiving and think it could be an opportunity for you to contact the site and have them link to you as well.</p>

<p>The domain <strong>outspokenmedia.com</strong> is currently linked to 1 of your competitors listed below and has a link strength (on a scale of 1-10) of 5:</p>

<ul>
<li>thunderseo.com:
<ul><li><a href="http://outspokenmedia.com/internet-marketing-conferences/beyond-rankings/">http://outspokenmedia.com/internet-marketing-conferences/beyond-rankings/</a></li>
<li><a href="http://outspokenmedia.com/seo/seo-trademark-application-terminated/">http://outspokenmedia.com/seo/seo-trademark-application-terminated/</a></li></ul></li>
</ul>',
			'howTo'       => '<p>Go to outspokenmedia.com and search for a contact form or general email address to send a note.</p>

<p>Ask for a link from the site owner or web master at outspokenmedia.com, who is linking to your competitors. <a href="mailto:enter@email.address?body=Hello, I am the owner of YOURDOMAIN.com. I noticed your web page http://outspokenmedia.com/seo/seo-trademark-application-terminated/ is linking to some resources that are similar to my site. I was wondering if you would consider adding a link to my site YOURDOMAIN.com if you think your visitors would find it useful.">CLICK HERE FOR AN E-MAIL SHORTCUT.</a></p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '1',
		),
		'176' => array(
			'title'       => '<p>Attempt to obtain a link from <a href="http://www.awwwards.com/websites/parallax/">awwwards.com</a>.</p>',
			'description' => '<p>Getting sites to link to your site is extremely important to improve the overall rankings of your site as each link counts as a \'vote\' for the content of your site. Some sites have more \'voting power\' or \'link strength\' in the eyes of the engines. We have identified a backlink one or more of your competitors are receiving and think it could be an opportunity for you to contact the site and have them link to you as well.</p>

<p>The domain <strong>awwwards.com</strong> is currently linked to 1 of your competitors listed below and has a link strength (on a scale of 1-10) of 6:</p>

<ul>
<li>digitaloperative.com:
<ul><li><a href="http://www.awwwards.com/best-websites/livebydesign">http://www.awwwards.com/best-websites/livebydesign</a></li>
<li><a href="http://www.awwwards.com/websites/animation/">http://www.awwwards.com/websites/animation/</a></li>
<li><a href="http://www.awwwards.com/websites/design/">http://www.awwwards.com/websites/design/</a></li>
<li><a href="http://www.awwwards.com/websites/e-commerce/">http://www.awwwards.com/websites/e-commerce/</a></li>
<li><a href="http://www.awwwards.com/websites/ecommerce/">http://www.awwwards.com/websites/ecommerce/</a></li>
<li><a href="http://www.awwwards.com/websites/navigation/">http://www.awwwards.com/websites/navigation/</a></li>
<li><a href="http://www.awwwards.com/websites/parallax/">http://www.awwwards.com/websites/parallax/</a></li>
<li><a href="http://www.awwwards.com/websites/silver/">http://www.awwwards.com/websites/silver/</a></li>
<li><a href="http://www.awwwards.com/websites/white/">http://www.awwwards.com/websites/white/</a></li></ul></li>
</ul>',
			'howTo'       => '<p>Go to awwwards.com and search for a contact form or general email address to send a note.</p>

<p>Ask for a link from the site owner or web master at awwwards.com, who is linking to your competitors. <a href="mailto:enter@email.address?body=Hello, I am the owner of YOURDOMAIN.com. I noticed your web page http://www.awwwards.com/websites/parallax/ is linking to some resources that are similar to my site. I was wondering if you would consider adding a link to my site YOURDOMAIN.com if you think your visitors would find it useful.">CLICK HERE FOR AN E-MAIL SHORTCUT.</a></p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '1',
		),
		'177' => array(
			'title'       => '<p>Attempt to obtain a link from <a href="http://feeds.feedburner.com/DigitalOperative">feeds.feedburner.com</a>.</p>',
			'description' => '<p>Getting sites to link to your site is extremely important to improve the overall rankings of your site as each link counts as a \'vote\' for the content of your site. Some sites have more \'voting power\' or \'link strength\' in the eyes of the engines. We have identified a backlink one or more of your competitors are receiving and think it could be an opportunity for you to contact the site and have them link to you as well.</p>

<p>The domain <strong>feeds.feedburner.com</strong> is currently linked to 3 of your competitors listed below and has a link strength (on a scale of 1-10) of 5:</p>

<ul>
<li>thunderseo.com:
<ul><li><a href="http://feeds.feedburner.com/komarketingassociates">http://feeds.feedburner.com/komarketingassociates</a></li>
<li><a href="http://feeds.feedburner.com/vehom">http://feeds.feedburner.com/vehom</a></li></ul></li>
<li>titan-seo.com:
<ul><li><a href="http://feeds.feedburner.com/thelocalbrand">http://feeds.feedburner.com/thelocalbrand</a></li></ul></li>
<li>digitaloperative.com:
<ul><li><a href="http://feeds.feedburner.com/DigitalOperative">http://feeds.feedburner.com/DigitalOperative</a></li></ul></li>
</ul>',
			'howTo'       => '<p>Go to feeds.feedburner.com and search for a contact form or general email address to send a note.</p>

<p>Ask for a link from the site owner or web master at feeds.feedburner.com, who is linking to your competitors. <a href="mailto:enter@email.address?body=Hello, I am the owner of YOURDOMAIN.com. I noticed your web page http://feeds.feedburner.com/DigitalOperative is linking to some resources that are similar to my site. I was wondering if you would consider adding a link to my site YOURDOMAIN.com if you think your visitors would find it useful.">CLICK HERE FOR AN E-MAIL SHORTCUT.</a></p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '1',
		),
		'178' => array(
			'title'       => '<p>Attempt to obtain a link from <a href="http://sdfilmfest.com/">sdfilmfest.com</a>.</p>',
			'description' => '<p>Getting sites to link to your site is extremely important to improve the overall rankings of your site as each link counts as a \'vote\' for the content of your site. Some sites have more \'voting power\' or \'link strength\' in the eyes of the engines. We have identified a backlink one or more of your competitors are receiving and think it could be an opportunity for you to contact the site and have them link to you as well.</p>

<p>The domain <strong>sdfilmfest.com</strong> is currently linked to 1 of your competitors listed below and has a link strength (on a scale of 1-10) of 5:</p>

<ul>
<li>digitaloperative.com:
<ul><li><a href="http://sdfilmfest.com/">http://sdfilmfest.com/</a></li></ul></li>
</ul>',
			'howTo'       => '<p>Go to sdfilmfest.com and search for a contact form or general email address to send a note.</p>

<p>Ask for a link from the site owner or web master at sdfilmfest.com, who is linking to your competitors. <a href="mailto:enter@email.address?body=Hello, I am the owner of YOURDOMAIN.com. I noticed your web page http://sdfilmfest.com/ is linking to some resources that are similar to my site. I was wondering if you would consider adding a link to my site YOURDOMAIN.com if you think your visitors would find it useful.">CLICK HERE FOR AN E-MAIL SHORTCUT.</a></p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '1',
		),
		'179' => array(
			'title'       => '<p>Attempt to obtain a link from <a href="http://www.aawebmasters.com/combinedpages.htm">aawebmasters.com</a>.</p>',
			'description' => '<p>Getting sites to link to your site is extremely important to improve the overall rankings of your site as each link counts as a \'vote\' for the content of your site. Some sites have more \'voting power\' or \'link strength\' in the eyes of the engines. We have identified a backlink one or more of your competitors are receiving and think it could be an opportunity for you to contact the site and have them link to you as well.</p>

<p>The domain <strong>aawebmasters.com</strong> is currently linked to 1 of your competitors listed below and has a link strength (on a scale of 1-10) of 5:</p>

<ul>
<li>titan-seo.com:
<ul><li><a href="http://www.aawebmasters.com/combinedpages.htm">http://www.aawebmasters.com/combinedpages.htm</a></li></ul></li>
</ul>',
			'howTo'       => '<p>Go to aawebmasters.com and search for a contact form or general email address to send a note.</p>

<p>Ask for a link from the site owner or web master at aawebmasters.com, who is linking to your competitors. <a href="mailto:enter@email.address?body=Hello, I am the owner of YOURDOMAIN.com. I noticed your web page http://www.aawebmasters.com/combinedpages.htm is linking to some resources that are similar to my site. I was wondering if you would consider adding a link to my site YOURDOMAIN.com if you think your visitors would find it useful.">CLICK HERE FOR AN E-MAIL SHORTCUT.</a></p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '1',
		),
		'180' => array(
			'title'       => '<p>Attempt to obtain a link from <a href="http://www.lacantinadoors.com/">lacantinadoors.com</a>.</p>',
			'description' => '<p>Getting sites to link to your site is extremely important to improve the overall rankings of your site as each link counts as a \'vote\' for the content of your site. Some sites have more \'voting power\' or \'link strength\' in the eyes of the engines. We have identified a backlink one or more of your competitors are receiving and think it could be an opportunity for you to contact the site and have them link to you as well.</p>

<p>The domain <strong>lacantinadoors.com</strong> is currently linked to 1 of your competitors listed below and has a link strength (on a scale of 1-10) of 5:</p>

<ul>
<li>digitaloperative.com:
<ul><li><a href="http://www.lacantinadoors.com/">http://www.lacantinadoors.com/</a></li></ul></li>
</ul>',
			'howTo'       => '<p>Go to lacantinadoors.com and search for a contact form or general email address to send a note.</p>

<p>Ask for a link from the site owner or web master at lacantinadoors.com, who is linking to your competitors. <a href="mailto:enter@email.address?body=Hello, I am the owner of YOURDOMAIN.com. I noticed your web page http://www.lacantinadoors.com/ is linking to some resources that are similar to my site. I was wondering if you would consider adding a link to my site YOURDOMAIN.com if you think your visitors would find it useful.">CLICK HERE FOR AN E-MAIL SHORTCUT.</a></p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '1',
		),
		'181' => array(
			'title'       => '<p>Attempt to obtain a link from <a href="http://www.kenshoo.com/testimonials/">kenshoo.com</a>.</p>',
			'description' => '<p>Getting sites to link to your site is extremely important to improve the overall rankings of your site as each link counts as a \'vote\' for the content of your site. Some sites have more \'voting power\' or \'link strength\' in the eyes of the engines. We have identified a backlink one or more of your competitors are receiving and think it could be an opportunity for you to contact the site and have them link to you as well.</p>

<p>The domain <strong>kenshoo.com</strong> is currently linked to 1 of your competitors listed below and has a link strength (on a scale of 1-10) of 5:</p>

<ul>
<li>digitaloperative.com:
<ul><li><a href="http://www.kenshoo.com/testimonials/">http://www.kenshoo.com/testimonials/</a></li></ul></li>
</ul>',
			'howTo'       => '<p>Go to kenshoo.com and search for a contact form or general email address to send a note.</p>

<p>Ask for a link from the site owner or web master at kenshoo.com, who is linking to your competitors. <a href="mailto:enter@email.address?body=Hello, I am the owner of YOURDOMAIN.com. I noticed your web page http://www.kenshoo.com/testimonials/ is linking to some resources that are similar to my site. I was wondering if you would consider adding a link to my site YOURDOMAIN.com if you think your visitors would find it useful.">CLICK HERE FOR AN E-MAIL SHORTCUT.</a></p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '1',
		),
		'182' => array(
			'title'       => '<p>Attempt to obtain a link from <a href="http://www.pinterest.com/ThunderActive/">pinterest.com</a>.</p>',
			'description' => '<p>Getting sites to link to your site is extremely important to improve the overall rankings of your site as each link counts as a \'vote\' for the content of your site. Some sites have more \'voting power\' or \'link strength\' in the eyes of the engines. We have identified a backlink one or more of your competitors are receiving and think it could be an opportunity for you to contact the site and have them link to you as well.</p>

<p>The domain <strong>pinterest.com</strong> is currently linked to 1 of your competitors listed below and has a link strength (on a scale of 1-10) of 5:</p>

<ul>
<li>thunderseo.com:
<ul><li><a href="http://www.pinterest.com/ThunderActive/">http://www.pinterest.com/ThunderActive/</a></li>
<li><a href="http://www.pinterest.com/pin/219620919302289661/">http://www.pinterest.com/pin/219620919302289661/</a></li></ul></li>
</ul>',
			'howTo'       => '<p>Go to pinterest.com and search for a contact form or general email address to send a note.</p>

<p>Ask for a link from the site owner or web master at pinterest.com, who is linking to your competitors. <a href="mailto:enter@email.address?body=Hello, I am the owner of YOURDOMAIN.com. I noticed your web page http://www.pinterest.com/ThunderActive/ is linking to some resources that are similar to my site. I was wondering if you would consider adding a link to my site YOURDOMAIN.com if you think your visitors would find it useful.">CLICK HERE FOR AN E-MAIL SHORTCUT.</a></p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '1',
		),
	),
	'105' => array(
		'183' => array(
			'title'       => '<p>Submit your site to <a href="http://www.fluther.com">Fluther</a>.</p>',
			'description' => '<p><a href="http://www.fluther.com">Fluther</a> is a user-generated &quot;Q&amp;A&quot; social network that helps people answer questions for one another. Helping people and giving your opinion on items related to your business and industry, while also adding a link back to your site,  helps you grow the relevance of your website and your overall online visibility. This will also help show your expertise in your industry.</p>',
			'howTo'       => '<p><a href="http://www.fluther.com/join/#content">Click here</a> to begin joining Fluther. Follow the directions to complete your submission. </p>

<p>To learn more details about how Fluther works, <a href="http://www.fluther.com/help/l">click here</a>. Be sure to read through the guidelines before you begin.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '2',
		),
		'184' => array(
			'title'       => '<p>Submit your site to <a href="http://www.plurk.com">Plurk</a>.</p>',
			'description' => '<p><a href="http://www.Plurk.com">Plurk</a> is a user-generated community allowing its users to share content, videos, and more. Each person\'s account is showcased on a timeline. Its a great backlinks resource for permanent, relevant links to your site.</p>',
			'howTo'       => '<p><a href="http://www.plurk.com/Users/showRegister">Click here</a> to begin signing up for a Plurk account. Follow the directions to complete your submission. </p>

<p>Consider selecting a specific article of yours or a blog post featured on your site, and several other useful websites or specific articles in your industry. That way, other users will find your account\'s other links valuable.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '3',
		),
		'185' => array(
			'title'       => '<p>Submit your site to <a href="http://www.jeqq.com">Jeqq.com</a></p>',
			'description' => '<p><a href="http://www.jeqq.com">Jeqq.com</a> is a quality social bookmarking website for users to post content, vote on links, and publish comments. The website allow its community of users to discover, categorize, rate, and share useful articles and content on the web. It is a great source to accumulate high quality, relevant links to a website.</p>',
			'howTo'       => '<p><a href="http://www.jeqq.com">Click here</a> to begin signing up for a Jeqq account. Follow the directions to complete your submission.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '4',
		),
		'186' => array(
			'title'       => '<p>Submit your site to <a href="http://www.spicypage.com">SpicyPage.com</a>.</p>',
			'description' => '<p><a href="http://www.spicypage.com">SpicyPage.com</a> is a user-generated blog sharing community and is a great source of permanent relevant links to blogs. The site allow its large community of users to discover, categorize, rate, and share useful articles and content on the website. It is a great source to accumulate high quality, relevant links to a website.</p>',
			'howTo'       => '<p><a href="http://www.spicypage.com/signup.cfm?section=">Click here</a> to begin signing up for a SpicyPage account. Follow the directions to complete your submission. Consider bookmarking your site and a few other useful websites or specific articles in your industry, so that other users will find your account\'s collection valuable.  </p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '5',
		),
		'187' => array(
			'title'       => '<p>Submit your site to <a href="http://www.bless1.net.com">BlessOne</a>.</p>',
			'description' => '<p> <a href="http://www.bless1.net.com">BlessOne</a> is a quality social bookmarking website for users to post content, vote on links, and publish comments. Following the motto, &quot;Bookingmarking the right way,&quot; the website allows its community of users to discover, categorize, rate, and share useful articles and content on the web. It is a great source to accumulate high quality, relevant links to a website.</p>',
			'howTo'       => '<p><a href="http://bless1.net/register.php">Click here</a> to begin signing up for a BlessOne account. Follow the directions to complete your submission. Consider bookmarking your site and a few other useful websites or specific articles in your industry, so that other users will find your account\'s collection valuable.  </p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '6',
		),
		'188' => array(
			'title'       => '<p>Submit your site to <a href="http://wwwQ1go.com">Q1GO</a>.</p>',
			'description' => '<p><a href="http://www.Q1go.com">Q1GO</a> is a quality social bookmarking website for users to post content, vote on links, and publish comments. The website allows its community of users to discover, categorize, rate, and share useful articles and content on the web. It is a great source to accumulate high quality, relevant links to a website.</p>',
			'howTo'       => '<p><a href="http://q1go.com/register.php">Click here</a> to begin signing up for a Q1GO account. Follow the directions to complete your submission. Consider bookmarking your site and a few other useful websites or specific articles in your industry, so that other users will find your account\'s collection valuable.  </p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '7',
		),
		'189' => array(
			'title'       => '<p>Submit your site to <a href="http://url.org">URL.ORGanizer</a>.</p>',
			'description' => '<p><a href="http://url.org">URL.ORGanizer</a> is a quality social bookmarking website for users to post information vote on links, and publish comments. The site allows its community of users to discover, categorize, rate, and share useful articles and content on the web. It is a great source to accumulate high quality, relevant links to a website.</p>',
			'howTo'       => '<p><a href="http://url.org/signup/">Click here</a> to begin signing up for a URL.ORGanizer account. Follow the directions to complete your submission. Consider bookmarking your site and a few other useful websites or specific articles in your industry, so that other users will find your account\'s collection valuable.  </p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '8',
		),
		'190' => array(
			'title'       => '<p>Sign up for a <a href="http://www.shareasale.com/r.cfm?b=170332&amp;u=418599&amp;m=21886&amp;urllink=&amp;afftrack=">KnowEm</a> account.</p>',
			'description' => '<p>It is important to have an online identity for your business, even in the digital social space. <a href="http://www.shareasale.com/r.cfm?B=170361&amp;U=418599&amp;M=21886">KnowEm</a> focuses on helping small and large businesses monitor and track their business\' brand, product, personal name or username on social media sites. </p>

<p>KnowEm will increase the identity of your business on more than 350 large and small social networks. In just a few clicks, the site will set up social network accounts and profiles for you so that you don\'t have to. </p>',
			'howTo'       => '<p><a href="http://knowem.com/register.php">Click here</a> to register your site on KnowEm. Please note, KnowEm has several payment options, including a basic free account. Click <a href="http://www.shareasale.com/r.cfm?u=418599&amp;b=170332&amp;m=21886&amp;afftrack=&amp;urllink=knowem%2Ecom%2Fpricing%2Ephp">here</a> for more details about their pricing choices before deciding which one to use. </p>

<p>To learn more about what KnowEm offers, click <a href="http://www.shareasale.com/r.cfm?u=418599&amp;b=170332&amp;m=21886&amp;afftrack=&amp;urllink=knowem%2Ecom%2Fabout%2Dus%2Ephp">here</a>.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '9',
		),
	),
	'108' => array(
		'191' => array(
			'title'       => '<p>Create a template email to ask for reviews.</p>',
			'description' => '<p>One of the best ways to make your customers leave review for your business is by asking them to do so. Review websites might sometimes filter reviews submitted by customers on the spot (based on IP address), so you need to diversify your review solicitation strategy. A great way to follow up and reach out to customers is via email. Creating a template follow-up email could help you in this endeavor.</p>',
			'howTo'       => '<p>Your template email should consist of the following elements:</p>

<ul>
<li>Greeting the customer and addressing them by name.</li>
<li>Thanking them for purchasing your product/service.</li>
<li>Asking them for their feedback.</li>
<li>Pointing them to the site where you\' like them to leave their feedback.</li>
</ul>

<p>In this example, the only variables are the name of the customer and the site you;d like the customers to leave their feedback. You should substitute these websites from time to time, so that you do not have all your reviews shown on the same website.</p>',
			'effort'      => '30',
			'impact'      => '15',
			'weight'      => '1',
		),
		'192' => array(
			'title'       => '<p>Sign up for service with <a href="http://www.customerlobby.com/">Customer Lobby</a> to help facilitate new reviews.</p>',
			'description' => '<p><a href="http://www.customerlobby.com/">Customer Lobby</a> solicits customer reviews on your behalf and distributes them to your website and social media accounts. While the reviews appear on your site, they are hosted by Customer Lobby. </p>

<p>A button on your site makes it easy for customers to add their review to your product or service at any time. This paid service uses customer data points provided by you (email, phone, mail, etc.) to actively recruit customers for reviews and testimonials.</p>',
			'howTo'       => '<p>Customer Lobby offers a free trial so you can learn more about how it works. Sign-up information can be found <a href="file://localhost/%3Chttps/::www.customerlobby.com:free-trial%3E">here</a>.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '11',
		),
		'193' => array(
			'title'       => '<p>Ask a select group of clients for reviews via email for Google+ Local.</p>',
			'description' => '<p>Google+ Local is the most important web property to populate with reviews. As discussed earlier, Google+ Local is the center of the local universe and having clients populate that property with reviews is tremendously important in getting your Google+ Local site to rank for your target terms.</p>',
			'howTo'       => '<p>To get initial reviews to your Google+ Local page, try reaching out to your existing customers via email and leverage the template you created in the Email Template task.</p>

<p>One trick on the email front would be to segment your client email list to only include clients with gmail addresses. If your customers have a gmail email account, then they already have an account at Google, and will be logged into Google. Thus, it would be relatively easy for them to go to Google+ Local and quickly provide a review.</p>',
			'effort'      => '15',
			'impact'      => '15',
			'weight'      => '2',
		),
		'194' => array(
			'title'       => '<p>Ask a select group of clients for reviews via email for Citysearch.</p>',
			'description' => '<p>Citysearch is one of the most important web properties to populate with reviews. Citysearch and it\'s parent company CityGrid distribute reviews to a significant number of sites and thus getting reviews into Citysearch can be extremely helpful from a distribution perspective.</p>',
			'howTo'       => '<p>To get initial reviews to your Citysearch listing, reach out to your existing customers via email and leverage the template you created in the Email Template task. Note that Citysearch allows users to log into their accounts using their Facebook profile.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '3',
		),
		'195' => array(
			'title'       => '<p>Ask a select group of clients for reviews via email for Yahoo! Local.</p>',
			'description' => '<p>Yahoo! is one of the most important web properties to populate with reviews. Google and others put a lot of credibility on Yahoo! reviews and sites with Yahoo! reviews appear to be more authoritative and rank more highly than those that don\'t.</p>',
			'howTo'       => '<p>To get initial reviews to your Yahoo! local listing, try reaching out to your existing customers via email and leverage the template you created in the Email Template task.</p>

<p>One trick on the email front would be to segment your client email list to only include clients with gmail addresses. If your customers have a Yahoo! email account, then they already have an account at Google, and will be logged into Google. Thus, it would be relatively easy for them to go to Google+ Local and quickly provide a review.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '4',
		),
		'196' => array(
			'title'       => '<p>Going forward, send follow-up emails to customers and ask for reviews.</p>',
			'description' => '<p>On an ongoing basis, it is important to follow up with clients right after you have completed a service or sold them a product. For example, doctors and dentists send a follow up email right after an appointment and ask their customer to provide feedback. </p>

<p>We recommend that you rotate your request between Google+ Local, Citysearch and Yahoo! with the highest emphasis on Google+ Local when providing them a link for reviews.</p>',
			'howTo'       => '<p>Send a follow up with clients right after you have completed a service or sold them a product service thanking them for their business. You should also use the Review Email Template that you created as part of the Email Template task. </p>

<p>As part of a review diversification strategy, you should rotate between Google+ Local, Citysearch and and Yahoo! Local so that you get reviews across those three important sites.</p>',
			'effort'      => '30',
			'impact'      => '20',
			'weight'      => '5',
		),
		'197' => array(
			'title'       => '<p>Add links to your profiles on Google+ Local, Yelp, Citysearch, Yellowpages on the sidebar of your website.</p>',
			'description' => '<p>You have to make it easy for your customers to be able to find your profiles on business directories, so that they can leave their reviews and feedback. One of the best ways to do that is by adding links to your profiles via your website. </p>

<p>We recommend that you add links to your Google+ Local listing, your Yahoo! Local listing, your Yelp listing, and your Citysearch listing. These sites are the most important online business directories in terms of reputation management, and the sites that users most often visit when looking for reviews about a business. Additionally, Citysearch distributes reviews to a large network of websites, which includes Bing, Yellowpages, Superpages, and others.</p>',
			'howTo'       => '<p>On the sidebar section of your website, paste any of the following links for users to click on and provide comments about your business:</p>

<p><strong>Feedback</strong></p>

<p>Please leave your feedback about our service on any of the following websites: </p>

<p>&lt;a href=?[Fill in with the URL of your Google+ Local listing]?&gt;Google+ Local&lt;/a&gt;</p>

<p>&lt;a href=?[Fill in with the URL of your Yahoo! Local listing]?&gt;Yahoo! Local&lt;/a&gt;</p>

<p>&lt;a href=?[Fill in with the URL of your Yelp listing]?&gt;Yelp&lt;/a&gt;</p>

<p>&lt;a href=?[Fill in with the URL of your Citysearch listing]?&gt;Citysearch&lt;/a&gt;</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '6',
		),
		'198' => array(
			'title'       => '<p>Put a link leading to a review site on your business cards and on your invoices.</p>',
			'description' => '<p>Customer reviews are increasingly important for growing your business. More reviews mean more exposure on search engines and prominence in local listings. List a review site where you want customers find you on all printed marketing materials including invoices and business cards.</p>',
			'howTo'       => '<p>Select a review site where your business is represented and customers can easily find you. List the exact URL of your business on a review site that you regularly maintain, such as Facebook or Google+ Local. Print the URL at the bottom of the invoice or business card.</p>',
			'effort'      => '30',
			'impact'      => '5',
			'weight'      => '7',
		),
		'199' => array(
			'title'       => '<p>Add a link to a review site in the signature line of your email.</p>',
			'description' => '<p>Adding a direct hyperlink to a review site in the signature of your email is an easy way to allow customers to view what others are saying about you and subtly encourage them to leave feedback of their own.</p>',
			'howTo'       => '<p>Add text to the bottom of your email signature with verbiage like, &quot;Read what our customers are saying and add your review to: (direct hyperlink) http://www.reviewsite.com/name-of-your-business/reviews.</p>

<p>As part of a review diversification strategy, you should rotate between Google+ Local, Citysearch and and Yahoo! Local so that you get reviews across those three important sites.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '8',
		),
		'200' => array(
			'title'       => '<p>Offer discount or freebie to a local blogger for a review of your product/service.</p>',
			'description' => '<p>Leverage the fan base of a blogger in your area by getting a review on his/her blog. Reach out with a special offer encouraging trial and review of your product.</p>',
			'howTo'       => '<p>People love incentives but don\'t like to feel \'bought\'. Establish a relationship with the blogger, and then comment on blog posts or email them separately. Broach the topic of a special trial offer of your product or service. If they seem resistant, thank them for their time. If open, politely tell them that if they like the product, you hope they will share it with their blog readers. You can\'t force a positive review.</p>

<p>Offers to suggest:</p>

<ul>
<li>Percentage off a service: Should be a greater discount than other published offers.</li>
<li>Referral fee: For all sales that mention the bloggers name when buying.</li>
<li>Free product: Always enticing!</li>
</ul>',
			'effort'      => '30',
			'impact'      => '5',
			'weight'      => '9',
		),
	),
	'109' => array(
		'201' => array(
			'title'       => '<p>Ask customers to +1 the page of the service/product they used and liked on Google+.</p>',
			'description' => '<p>Social sharing is increasingly important for growing your business. It\'s the new \'word-of-mouth\' marketing. Your business is amplified when customers endorse you via Google+ to their colleagues and friends. You are much more likely to gain a review if you ask for it and let people know where they can review you.</p>',
			'howTo'       => '<p>After an online purchase is complete, consider including a Google+ review request on the e-receipt. People are more inclined to respond immediately after a purchase. Words listed on email receipt can say something like: &quot;Like this product? Please click on the +1 button on this page, and share your purchase with followers and friends on Google+.&quot; </p>

<p>Another method for requesting endorsements is to follow-up via email with the customer five days after purchase with the same verbiage as above.</p>',
			'effort'      => '15',
			'impact'      => '15',
			'weight'      => '1',
		),
		'202' => array(
			'title'       => '<p>Ask customers to share the page of the service/product they used and liked on Facebook.</p>',
			'description' => '<p>Social sharing is increasingly important for growing your business. It\'s the new \'word-of-mouth\' marketing. Your business is amplified when customers endorse you via Facebook to their colleagues and friends. You are much more likely to gain a review if you ask for it and let people know where they can review you.</p>',
			'howTo'       => '<p>After an online purchase is complete, consider including a Facebook review request on the e-receipt or order confirmation email. People are more inclined to respond immediately after a purchase. Words listed on email receipt can say something like: &quot;Like this product? Please share this page with your friends on Facebook.&quot; </p>

<p>Another method for requesting endorsements is to follow-up via email with the customer five days after purchase with the same verbiage as above.</p>',
			'effort'      => '15',
			'impact'      => '7',
			'weight'      => '2',
		),
		'203' => array(
			'title'       => '<p>Ask customers to tweet the page of the service/product they used and liked on Twitter.</p>',
			'description' => '<p>Social sharing is increasingly important for growing your business. It\'s the new &quot;word-of-mouth&quot; marketing. Your business is amplified when customers endorse you via Twitter to their colleagues and friends. Your business is much more likely to gain a review if you ask for it and let people know where they can review you.</p>',
			'howTo'       => '<p>After an online purchase is complete, consider including a Twitter request on the e-receipt or order confirmation email. People are more inclined to respond immediately after a purchase. </p>

<p>Words listed on an email receipt can say something as simple and easy as, &quot;Like this product? Please share this page with your friends on Twitter.&quot; Another method for requesting endorsements is to send a follow-up via email with the customer five days after the purchase with the same verbiage as above.</p>',
			'effort'      => '15',
			'impact'      => '7',
			'weight'      => '3',
		),
		'204' => array(
			'title'       => '<p>Ask frequent customers to check-in while visiting your business place via Foursquare or Facebook. </p>',
			'description' => '<p>Location-based tools, such as Foursquare or Facebook, make it easy to for customers to \'check-in\' and share their location with friends and followers. This strategy often increases reviews on your product/service, business awareness, and physical traffic to your store. Promote specials for frequent customers and users of the apps.</p>',
			'howTo'       => '<p>Ways to encourage participation with your location via FourSquare or Facebook:</p>

<ul>
<li>In-store signage asking people to check-in. After showing their mobile phone to you or your staff, they receive the deal.</li>
<li>Promote location-based check-ins on your website and other social media.</li>
<li>Offer a great deal that requires a larger group to check in before unlocking.</li>
</ul>',
			'effort'      => '15',
			'impact'      => '7',
			'weight'      => '4',
		),
		'205' => array(
			'title'       => '<p>Invite email contacts to like your Facebook page.</p>',
			'description' => '<p>Many of your email contacts may not be aware of your Facebook Business Page. If you have less than 5,000 email contacts, it\'s easy to import their emails into Facebook and invite them to like you.</p>',
			'howTo'       => '<p>You can import your email addresses to Facebook with a CSV/Excel file or sync them to your web-based email (Google, Yahoo, Hotmail, etc.). Directions can be found <a href="file://localhost/%3Chttp/::www.facebook.com:notes:facebook-pages:using-email-contacts-to-build-your-page:10150115103649822%3E">here.</a></p>

<p>Send your contacts a simple request asking them to \'Like\' your Business Page.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '5',
		),
		'206' => array(
			'title'       => '<p>Invite email contacts to connect on LinkedIn.</p>',
			'description' => '<p>Many of your email contacts may not be aware of your business page on LinkedIn. It\'s easy to import your web-based email contacts, and then connect and invite them to follow you.</p>',
			'howTo'       => '<p>Connecting and importing web-based email contacts to your LinkedIn account is easy:</p>

<ul>
<li>Click on top, right button that says &quot;Add Connections.&quot;</li>
<li>Select your email program(s) one at a time.</li>
<li>Add you email address for that email platform if it isn\'t listed.</li>
<li>Click continue.</li>
<li>Allow LinkedIn access.</li>
<li>Select individual you\'d like to contact.</li>
<li>Send message asking them to connect.</li>
<li>Work through each of the email programs you have.</li>
</ul>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '6',
		),
		'207' => array(
			'title'       => '<p>Invite LinkedIn connections to follow your LinkedIn page.</p>',
			'description' => '<p><a href="http://www.linkedin.com">LinkedIn</a> is the top social media site for business professionals. It has added content streams to personal and company pages making it a strong inbound marketing platform.</p>',
			'howTo'       => '<p>Once your LinkedIn company page is complete, ask connections and outside groups to follow you:</p>

<ul>
<li>Send a message to your connections with your LinkedIn Company Page URL asking them to follow you.</li>
<li>Email your contacts directly, include your LinkedIn page URL, and ask them to follow you.</li>
<li>Post the URL of your page on all social media channels that you participate in so that it can easily be accessed.</li>
<li>Review and redo your page at least once every 6 months.</li>
</ul>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '7',
		),
	),
	'112' => array(
		'208' => array(
			'title'       => '<p>Reply to a mention of your business on {social media site}.</p>',
			'description' => '',
			'howTo'       => '',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '1',
		),
	),
	'115' => array(
		'209' => array(
			'title'       => '<p>Start to follow and connect with industry leaders and influencers on Twitter.</p>',
			'description' => '<p>Twitter is a top social media site known for short snippets of information delivered in 140 characters or less. You can reply to individuals, reply to comments, or start conversations with \'hash tags\' around areas of interest. </p>

<p>When starting out your Twitter handle, don\'t feel like you have to start tweeting a lot right away. Just start to use Twitter as a listening tool.</p>',
			'howTo'       => '<p>Look for leaders in your industry and check out who they follow on Twitter. Follow those of interest whom you find interesting. Be sure to read Tweets made by unknown individuals before following them. Retweet tweets of interest, and mention their posts in your Twitter feed. With consistent activity, you\'ll begin to build a following over time.</p>',
			'effort'      => '30',
			'impact'      => '10',
			'weight'      => '1',
		),
		'210' => array(
			'title'       => '<p>Answer questions on <a href="http://www.quora.com">Quora</a>.</p>',
			'description' => '<p>Quora is the fastest growing Question and Answer platform. Quora is integrated nicely with social media sites, making it very easy to share questions and answers you offer with your followers.</p>',
			'howTo'       => '<p><a href="https://www.quora.com/signup">Click here</a> to sign up for Quora. Then search for topics around your area of expertise and start answering questions. You can also vote up other answers and start to follow other thought leaders in your space. Be sure to share your answers across LinkedIn, Twitter, Facebook and Google+.</p>',
			'effort'      => '60',
			'impact'      => '10',
			'weight'      => '10',
		),
		'211' => array(
			'title'       => '<p>Answer questions on <a href="http://www.answers.yahoo.com">Yahoo! Answers</a>.</p>',
			'description' => '<p>Yahoo! was one of the first sites to launch a <a href="https://www.answsers.yahoo.com">Question and Answer platform</a>, and it still provides a largely active community of users.</p>',
			'howTo'       => '<p><a href="https://www.answsers.yahoo.com">Click here</a> to go to Yahoo! Answers. Go to the Answer section, and click on the topics around your area of expertise and start answering questions. </p>

<p>You can also vote up other answers and start to follow other thought leaders in your space. Be sure to share your answers across LInkedIn, Twitter, Facebook and Google+.</p>',
			'effort'      => '60',
			'impact'      => '10',
			'weight'      => '11',
		),
		'212' => array(
			'title'       => '<p>Answer questions on <a href="http://www.linkedin.com/answers/">LinkedIn Answers</a>.</p>',
			'description' => '<p><a href="http://www.linkedin.com/answers/">LinkedIn Answers</a> provides a great area to add more credibility to your personal profile and business page within LinkedIn. The site makes it easy by showing you questions related to your industry.</p>',
			'howTo'       => '<p><a href="http://www.linkedin.com/answers/">Click here</a> to go to LinkedIn Answers. Click on Answer Questions, and then search for your industry or area of expertise. You can also vote up other answers and start to follow other thought leaders in your space. Be sure to share your answers across LinkedIn, Twitter, Facebook and Google+.</p>',
			'effort'      => '60',
			'impact'      => '10',
			'weight'      => '14',
		),
		'213' => array(
			'title'       => '<p>Sign up for <a href="http://www.google.com/alerts">Google Alerts</a>.</p>',
			'description' => '<p><a href="http://www.google.com/alerts">Google Alerts</a> allow you to receive the latest news headline links that contain a specific keyword or phrase in Google. It can be a pretty powerful tool to understand who is saying what about your company and/or industry. We\'ll leverage Google Alerts for task in an upcoming course.</p>

<p>For example, you can track your business name or key phrases in Google to see when someone links to your site. The Google Alert tool also provide a way for you to monitor your industry or competition.</p>

<p>In addition, if a site is linking to your competitors, you can use the information to potentially get a link to your business in Google.</p>',
			'howTo'       => '<p><a href="http://www.google.com/alerts">Click here</a> to set-up Google Alerts for your business.</p>',
			'effort'      => '15',
			'impact'      => '1',
			'weight'      => '16',
		),
		'214' => array(
			'title'       => '<p>Thank and follow back new followers on Twitter.</p>',
			'description' => '<p>Thanking people for following you on Twitter is great etiquette and not that difficult to accomplish with the right auto responder tools. You can also do this directly via one of your social media monitoring tools.</p>',
			'howTo'       => '<p>If you\'re interested in an automation feature for new followers, review Twitter support tips <a href="https://support.twitter.com/articles/76915">here</a>. </p>

<p>It\'s easy to monitor new followers in your Twitter dashboard. If they offer quality content and material of interest, you are able to click through to their
Twitter account and follow back.</p>

<p>Twitter\'s FAQs and best practices can be
read <a href="https://support.twitter.com/articles/68916-following-rules-and-best-practices">here</a>.</p>',
			'effort'      => '30',
			'impact'      => '5',
			'weight'      => '2',
		),
		'215' => array(
			'title'       => '<p>Start to follow and connect with colleagues and clients on LinkedIn.</p>',
			'description' => '<p>Look for colleagues and clients on LinkedIn. Send a connection request. and then follow their updates and join groups where they belong. Note that adding a personal comment often is a better approach when asking for a connection.</p>',
			'howTo'       => '<p>Here are some strategies to find LinkedIn connections:</p>

<ul>
<li>Import Email Connections: Use the Add Connections to see if anyone in your email contacts are on LinkedIn.</li>
<li>Colleagues: Use the Colleagues area to find people work with you or that you previously worked for.</li>
<li>Alumni: Use the Alumni section to find people who attended the same schools and universities as you.</li>
<li>People You May Know: Use the People You May Know area, find people who you might know based on mutual connections.</li>
<li>Participate in LinkedIn Groups and Answers.</li>
<li>Add a link to your LinkedIn profile&lt; on your website, emails, and other social profiles.</li>
</ul>',
			'effort'      => '30',
			'impact'      => '10',
			'weight'      => '3',
		),
		'216' => array(
			'title'       => '<p>Join groups in LinkedIn relevant to your industry.</p>',
			'description' => '<p>Joining <a href="http://www.linkedin.com">LinkedIn</a> groups specific to your industry can provide invaluable leads, connections, and industry news.</p>',
			'howTo'       => '<p>How to join <a href="http://www.linkedin.com">LinkedIn</a> groups:</p>

<p>*Select the Groups tab at the top of your LinkedIn page.
*Select Groups Directory from the drop down list.
*Search keywords specific to your business or interest
*Join group.</p>',
			'effort'      => '30',
			'impact'      => '10',
			'weight'      => '4',
		),
		'217' => array(
			'title'       => '<p>Start to follow and connect with industry leaders and influencers on Google+.</p>',
			'description' => '<p>Connecting and following leaders specific to your industry can provide invaluable leads, connections, and industry news. Google+ allows you to create circles forspecific groups and areas of interest. Your \'business\' circle should include leaders in your industry.</p>',
			'howTo'       => '<p>In your Google+ homepage, look at \'You May Know\' for first suggestions on who to follow on Google+. Search colleague and other industry leaders. Follow these people, then reach out as appropriate with your questions and comments to start to build a relationship.</p>',
			'effort'      => '60',
			'impact'      => '20',
			'weight'      => '5',
		),
		'218' => array(
			'title'       => '<p>Answer questions on <a href="http://www.answers.com">Answers.com</a>.</p>',
			'description' => '<p>Answer.com is one of the largest Question and Answer platforms on the Internet. By answering questions here and posting your answers on Facebook, you can elevate your online authority and relevancy.</p>',
			'howTo'       => '<p><a href="http://answers.com">Click here</a> to create an account at Answers.com and start answering questions. You should then share your answers via Facebook, Google+, Twitter and LinkedIn to continue to build up your social credibility.</p>',
			'effort'      => '60',
			'impact'      => '10',
			'weight'      => '8',
		),
	),
	'116' => array(
		'219' => array(
			'title'       => '<p>Create an editorial calendar for your blog.</p>',
			'description' => '<p>Developing an editorial calendar that pre-plans weekly posts is the easiest way to stay on track with publishing new content to a blog. Often creating categories and developing titles under each for proposed posts helps you stay focused.</p>',
			'howTo'       => '<p>Create a simple spreadsheet with the basic information. This is a guide only, so you can add columns as needed. Try to publish weekly but at a minimum, at least twice a month. Add text in each cell and create at least one-month at a time. Working with deadlines one week ahead of planned post dates allows time for last-minute changes.</p>

<p>Here are some columns to think about including:</p>

<ul>
<li>Publish Date</li>
<li>Due Date</li>
<li>Author (if multiple contributors)</li>
<li>Title of Post (title can change once post is complete, but basic titles help you remember planned content)</li>
<li>Category</li>
<li>Type of content: blog, video, article, images, etc.</li>
<li>Targeted Keywords</li>
</ul>',
			'effort'      => '180',
			'impact'      => '25',
			'weight'      => '1',
		),
		'220' => array(
			'title'       => '<p>Write a blog article that compiles famous quotes related to your business and promote it via social media.</p>',
			'description' => '<p>Content that engages potential/existing clients is always a great blog post. Be sure to always direct customers to the page or post on your site, so that they can see more of the quality content you offer. After post is created, promote it to all of your social media channels. Having people share and link to your content is a strong inbound marketing strategy.</p>',
			'howTo'       => '<p>Create a new page in your CMS on Famous Quotes. Review famous quotes on the web to find fun and informational sayings of interest to your customers. Write a  unique introduction paragraph explaining how you selected the items. </p>

<p>Place your quote in quotation marks from your CMS, so that people and search engines know the material was copied from the web. Always avoid duplicating content as you will likely be penalized by the search engines or clearly reference the source.</p>

<p>Try to publish blog posts weekly but aim for a minimum of at least once a month. Search engines love fresh content, and blogging has a strong impact on rankings. </p>

<p>Post guidelines:</p>

<ul>
<li>Aim for 400-600 words at a minimum.</li>
<li>Place targeted keywords in the first paragraph or as they naturally fit.</li>
<li>Cross-link to other articles on your site.</li>
<li>Include hyperlinks out to external quality resources. Linking out to others adds credibility to your blog and encourages other authors to link back to you.</li>
<li>Set your CMS to have external links open in a new window.</li>
<li>Include images and other multi-media in your posts whenever possible. It\'s good for people and search engines!</li>
<li>Include tag text on all images and multi-media.</li>
</ul>

<p>Your social media promotion should direct customers to your post. Text might be something like, &quot;Have fun this week\'s top quotes on the furniture industry. Share with friends and colleagues!&quot; Provide shortened link from a provider like <a href="https://bitly.com">bit.ly</a> that goes directly to your post.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '10',
		),
		'221' => array(
			'title'       => '<p>Write a blog article that summarizes good blogs in your industry and promote it via social media.</p>',
			'description' => '<p>Content that adds value to potential/existing clients is always a great blog post. After post is created, promote it to all of your social media channels. Having people share and link to your content is one of the strongest inbound marketing strategies.</p>',
			'howTo'       => '<p>Review industry leader\'s blogs or other web content each week to find articles of most value to your customers. Provide a hyperlink to the original news source for full article. This is called content curating. Write a few unique sentences explaining why the article or post is of interest. NEVER duplicate what was originally written as you will likely be penalized by the search engine for duplicate content.</p>

<ul>
<li>Aim for 400-600 words at a minimum.</li>
<li>Place targeted keywords in the first paragraph or as they naturally fit.</li>
<li>Cross-link to other articles on your site.</li>
<li>Include hyperlinks out to external quality resources. Linking out to others adds credibility to your blog and encourages other authors to link back to you.</li>
<li>Set your CMS to have external links open in a new window.</li>
<li>Include images and other multi-media in your posts whenever possible. It\'s good for people and search engines!</li>
<li>Include tag text on all images and multi-media.</li>
</ul>

<p>Your social media promotion should direct customers to your post. Text might be something like, &quot;Check out this week\'s top xxx news and learn how it might impact you.&quot; Provide shortened link from a provider like <a href="https://bitly.com">bit.ly</a> that goes directly to your post.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '11',
		),
		'222' => array(
			'title'       => '<p>Write a blog article that profiles customer service or various employees in your business.</p>',
			'description' => '<p>Content that engages potential/existing clients is always a great blog post. After your blog post is created, promote it to all of your social media channels. Having people share and link to your content is one of the strongest inbound marketing strategies.</p>',
			'howTo'       => '<p>Create a new post in your CMS on Top Performers. Customers and employees like reading about top performers. Write a few unique sentences on each person\'s background and why they are being awarded. NEVER duplicate what was originally written as you will likely be penalized by the search engine for duplicate content. </p>

<p>Try to publish weekly but aim for a minimum of at least once a month. Search engines love fresh content and it has a strong impact on rankings.</p>

<p>Post guidelines:</p>

<ul>
<li>Aim for 400-600 words at a minimum.</li>
<li>Place targeted keywords in the first paragraph or as they naturally fit.</li>
<li>Cross-link to other articles on your site.</li>
<li>Include hyperlinks out to external quality resources. Linking out to others adds credibility to your blog and encourages other authors to link back to you.</li>
<li>Set your CMS to have external links open in a new window.</li>
<li>Include images and other multi-media in your posts whenever possible. It\'s good for people and search engines!</li>
<li>Include tag text on all images and multi-media.</li>
</ul>

<p>Your social media promotion should direct customers to your post. Text might be something like, &quot;Thanks to our top performing sales team. Learn more about them and why we think they\'re great!&quot; Provide shortened link from a provider like <a href="&quot;https://bitly.com&quot;">bit.ly</a> that goes directly to your post.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '12',
		),
		'223' => array(
			'title'       => '<p>Write a blog article on the top 3 myths about your industry and and promote it via social media.</p>',
			'description' => '<p>Content that adds value or is entertaining to potential/existing clients is always a great blog post. After post is created, promote it to all of your social media channels. Having people share and link to your content is one of the strongest inbound marketing strategies.</p>',
			'howTo'       => '<p>In your CMS, add a new post on the Top 3 [industry] Myths. Include how they originated and the truth on each point. NEVER duplicate content written by someone else as you will likely be penalized by the search engine for duplicate content. </p>

<p>Post guidelines:</p>

<ul>
<li>Aim for 400-600 words at a minimum.</li>
<li>Place targeted keywords in the first paragraph or as they naturally fit.</li>
<li>Cross-link to other articles on your site.</li>
<li>Include hyperlinks out to external quality resources. Linking out to others adds credibility to your blog and encourages other authors to link back to you.</li>
<li>Set your CMS to have external links open in a new window.</li>
<li>Include images and other multi-media in your posts whenever possible. It\'s good for people and search engines!</li>
<li>Include tag text on all images and multi-media.</li>
</ul>

<p>Your social media promotion should direct customers to your post. Text might be something like, &quot;Truth or fiction? Learn more about the top 3 myths in the <a href="https://bitly.com&quot;">industry].&quot; Provide shortened link from a provider like [bit.ly</a> that goes directly to your post.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '13',
		),
		'224' => array(
			'title'       => '<p>Create a list of the top 10 questions you get from prospective clients. Use each question as its own blog article, and promote via social media.</p>',
			'description' => '<p>The top 10 questions from prospective clients is a great series for blog posts, and  it will add value to potential/existing clients. Be sure to direct people to the post online when the question comes up. This is a great way to highlight your content that people might not be aware exists. Encouraging people to share and link to your website\'s content is a strong inbound marketing strategy.</p>',
			'howTo'       => '<p>Find the top 10 questions by culling your CRM, asking the customer service team, or gathering customer feedback forms. Write a post around each of the 10 questions individually. Provide answer and hyperlink to more information on your site if applicable.</p>',
			'effort'      => '120',
			'impact'      => '25',
			'weight'      => '2',
		),
		'225' => array(
			'title'       => '<p>Write a blog article about various &quot;Resources&quot; and helpful websites in your industry, and then promote via social media.</p>',
			'description' => '<p>&quot;Curating&quot; quality content from around the web, then promoting it in social media, adds value to potential/existing clients. Always credit the original author and provide a hyperlink to the article. Never duplicate other\'s content onto your site.</p>',
			'howTo'       => '<p>Review industry leader\'s blogs or other web content to find articles of most value to your customers. Write a few unique sentences explaining why the article or post is of interest. NEVER duplicate what was originally written as you will likely be penalized by the search engine for duplicate content.</p>

<p>Try to publish weekly but aim for a minimum of at least once a month. Search engines love fresh content and it has a strong impact on rankings. </p>

<p>Post guidelines:</p>

<ul>
<li>Aim for 400-600 words at a minimum.</li>
<li>Place targeted keywords in the first paragraph or as they naturally fit.</li>
<li>Cross-link to other articles on your site.</li>
<li>Include hyperlinks out to external quality resources. Linking out to others. adds credibility to your blog and encourages other authors to link back to you.</li>
<li>Set your CMS to have external links open in a new window.</li>
<li>Include images and other multi-media in your posts whenever possible. It\'s good for people and search engines!
*Include tag text on all images and multi-media.</li>
</ul>

<p>Your social media promotion should direct customers to your post. Text might be something like: &quot;Check out this week\'s top xxx news and learn how it might impact you.&quot; Provide shortened link from a provider like <a href="https://bitly.com">bit.ly</a> that goes directly to your post.</p>',
			'effort'      => '120',
			'impact'      => '10',
			'weight'      => '3',
		),
		'226' => array(
			'title'       => '<p>Write a blog that includes a &quot;Glossary of Terms&quot; that are relevant to your industry, and then promote via social media.</p>',
			'description' => '<p>Content that adds value to potential/existing clients is always a great blog post. After post is created, promote it to all of your social media channels. Having people share and link to your content is a strong inbound marketing strategy.</p>',
			'howTo'       => '<p>In your CMS, add a new post on Glossary of Terms. Write a one or two  unique sentences for each Term. Never duplicate what was originally written as you will likely be penalized by the search engine. </p>

<p>Try to publish weekly but aim for a minimum of at least once a month. Search engines love fresh content and it has a strong impact on rankings. </p>

<p>Post guidelines:</p>

<ul>
<li>Aim for 400-600 words at a minimum.</li>
<li>Place targeted keywords in the first paragraph or as they naturally fit.</li>
<li>Cross-link to other articles on your site.</li>
<li>Include hyperlinks out to external quality resources. Linking out to others adds credibility to your blog and encourages other authors to link back to you.</li>
<li>Set your CMS to have external links open in a new window.</li>
<li>Include images and other multi-media in your posts whenever possible. It\'s good for people and search engines!</li>
<li>Include tag text on all images and multi-media.</li>
<li>Your social media promotion should direct customers to your post. Text might be something like, &quot;Check out this week\'s top XXX news, and learn how it might impact you.&quot;</li>
<li>Provide shortened link from a provider, such as <a href="https://bitly.com">bit.ly</a> that goes directly to your post.</li>
</ul>',
			'effort'      => '120',
			'impact'      => '10',
			'weight'      => '4',
		),
		'227' => array(
			'title'       => '<p>Write a blog article about an upcoming event that your business will attend and promote it via social media.</p>',
			'description' => '<p>Write a blog article about an upcoming event that you and/or your employees will attend and promote it via social media. Ideas for events to write about can include a conference, a trade show, or a seminar. Content that adds value to potential/existing clients is always a great blog post. </p>

<p>Be sure to always direct customers to the page or post on your site, so that they can see more of the quality content you offer. After your blog post is created, promote it to all of your social media channels. Having people share and link to your content is a strong inbound marketing strategy.</p>',
			'howTo'       => '<p>In your CMS, add a new blog post about the upcoming event. Write a description of the event and provide address/directions, time, and date. NEVER duplicate what was originally written as you will likely be penalized by the search engine for duplicate content. </p>

<p>Post guidelines:</p>

<ul>
<li>Aim for 400-600 words at a minimum.</li>
<li>Place targeted keywords in the first paragraph or as they naturally fit.</li>
<li>Cross-link to other articles on your site.</li>
<li>Include hyperlinks out to external quality resources. Linking out to others adds credibility to your blog and encourages other authors to link back to you.</li>
<li>Set your CMS to have external links open in a new window.</li>
<li>Include images and other multi-media in your posts whenever possible. It\'s good for people and search engines!</li>
<li>Include tag text on all images and multi-media.</li>
<li>Your social media promotion should direct customers to your post. Text might be something like, &quot;Check out this week\'s upcoming industry event, and learn how it might impact you.&quot;</li>
<li>Provide shortened link from a provider, such as <a href="https://bitly.com">bit.ly</a> that goes directly to your post.</li>
</ul>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '5',
		),
		'228' => array(
			'title'       => '<p>Write a blog article about a client success story/case study that summarizes how you helped the client and promote it via social media.</p>',
			'description' => '<p>Content that shows your previous success to potential/existing clients is always a great blog post. After your blog post is created, promote it to all of your social media channels. Having people share and link to your content is a strong &lt;i&gt;inbound marketing strategy.</p>',
			'howTo'       => '<p>In your CMS, add a new post about your Client Case Study. Write one description about the challenge your client faced and how your product/service helped resolve the issue. Provide specific data whenever possible. NEVER duplicate other case studies written as you will likely be penalized by the search engine for duplicate content. </p>

<p>Post guidelines:</p>

<ul>
<li>Aim for 400-600 words at a minimum.</li>
<li>Place targeted keywords in the first paragraph or as they naturally fit.</li>
<li>Cross-link to other articles on your site.</li>
<li>Include hyperlinks out to external quality resources. Linking out to others adds credibility to your blog and encourages other authors to link back to you.</li>
<li>Set your CMS to have external links open in a new window.</li>
<li>Include images and other multi-media in your posts whenever possible. It\'s good for people and search engines!</li>
<li>Include tag text on all images and multi-media.</li>
</ul>

<p>Your social media promotion should direct customers to your post. Text might be something like, &quot;Don\'t miss this week\'s client highlight and learn how our company helped make a difference.&quot; Provide shortened link from a provider like <a href="https://bitly.com">bit.ly</a> that goes directly to your post.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '6',
		),
		'229' => array(
			'title'       => '<p>Write a blog article that provides a buying decision checklist for products/services similar to your business and promote it via social media.</p>',
			'description' => '<p>Write a blog article that provides a buying decision checklist for products/services similar to or same as yours and promote it via social media.
Content that adds value to potential/existing clients is always a great blog post. Be sure to always direct customers to the page or post on your site, so that they can see more of the quality content you offer. After post is created, promote it to all of your social media channels.</p>',
			'howTo'       => '<p>&lt;In your CMS, add a new post on the Buying Decision Checklist. Write a list of all items involved in making a decision in your product/service category. NEVER duplicate what was originally written by someone else as you will likely be penalized by the search engine for duplicate content.  </p>

<p>Post guidelines:</p>

<ul>
<li>Aim for 400-600 words at a minimum.</li>
<li>Place targeted keywords in the first paragraph or as they naturally fit.</li>
<li>Cross-link to other articles on your site.</li>
<li>Include hyperlinks out to external quality resources. Linking out to others adds credibility to your blog and encourages other authors to link back to you.</li>
<li>Set your CMS to have external links open in a new window.</li>
<li>Include images and other multi-media in your posts whenever possible. It\'s good for people and search engines!</li>
<li>Include tag text on all images and multi-media.</li>
</ul>

<p>Your social media promotion should direct customers to your post. Text might be something like, &quot;Trying to decide which widget is right for you? Check out our buying checklist to ensure you make the right decisions.&quot; Provide shortened link from a provider like <a href="https://bitly.com">bit.ly</a> that goes directly to your post.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '7',
		),
		'230' => array(
			'title'       => '<p>Write a blog article about an emerging trend in your industry and promote it via social media.</p>',
			'description' => '<p>Content that adds value to potential/existing clients is always a great blog post. After the blog post is created, promote it to all of your social media channels. Having people share and link to your content is a strong inbound marketingstrategy.</p>',
			'howTo'       => '<p>In your CMS, add a new post on the latest Industry Trend. Write a list of all items involved in making a decision in your product/service category. NEVER duplicate what was originally written by someone else as you will likely be penalized by the search engine for duplicate content.</p>

<p>Post guidelines:</p>

<ul>
<li>Aim for 400-600 words at a minimum.</li>
<li>Place targeted keywords in the first paragraph or as they naturally fit.</li>
<li>Cross-link to other articles on your site.</li>
<li>Include hyperlinks out to external quality resources. Linking out to others adds credibility to your blog and encourages other authors to link back to you.</li>
<li>Set your CMS to have external links open in a new window.</li>
<li>Include images and other multi-media in your posts whenever possible. It\'s good for people and search engines!</li>
<li>Include tag text on all images and multi-media.</li>
</ul>

<p>Your social media promotion should direct customers to your post. Text might be something like, &quot;Trying to decide which widget is right for you? Check out our buying checklist to ensure you make the right decisions.&quot; Provide shortened link from a provider like <a href="&quot;https://bitly.com&quot;">bit.ly</a> that goes directly to your post.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '8',
		),
		'231' => array(
			'title'       => '<p>Write a blog article about a new product/service that you launch and promote it via social media.</p>',
			'description' => '<p>Content that adds value to potential/existing clients is always a great blog post. Never overlook promoting new products or services on your blog. Be sure to always direct customers to the page or post on your site, so that they can see more of the quality content you offer. After post is created, promote it to all of your social media channels. Having people share and link to your content is one of the strongest inbound marketing strategies.</p>',
			'howTo'       => '<p>In your CMS, add a new post on your Newest Service. Include when it launches, the newest features, and benefits to the customer. Provide a limited time introductory offer if applicable. NEVER duplicate content written by someone else as you will likely be penalized by the search engine for duplicate content. </p>

<p>Post guidelines:</p>

<ul>
<li>Aim for 400-600 words at a minimum.</li>
<li>Place targeted keywords in the first paragraph or as they naturally fit.</li>
<li>Cross-link to other articles on your site.</li>
<li>Include hyperlinks out to external quality resources. Linking out to others adds credibility to your blog and encourages other authors to link back to you.</li>
<li>Set your CMS to have external links open in a new window.</li>
<li>Include images and other multi-media in your posts whenever possible. It\'s good for people and search engines!</li>
<li>Include tag text on all images and multi-media.</li>
</ul>

<p>Your social media promotion should direct customers to your post. Text might be something like, &quot;We\'ve solved your cleaning problem! Announcing our new earth friendly scrub, check out the details on our site.&quot; Provide shortened link from a provider like <a href="https://bitly.com">bit.ly</a> that goes directly to your post.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '9',
		),
	),
	'117' => array(
		'232' => array(
			'title'       => '<p>Post your company logo to various photo sites.</p>',
			'description' => '<p>Images and video provide a great inbound marketing opportunity for your business. Have you ever looked at Google Images to find what you\'re looking for? Logos, team members, products all have value to the search engines and your customers. Be sure to optimize them properly so they can easily be found.</p>',
			'howTo'       => '<p>Consider sites where people commonly post images and potential customers night be such as <a href="http://www.flickr.com">Flickr</a>, <a href="http://imgur.com/">Imgur,</a><a href="http://instagram.com/">Instagram,</a> <a href="http://picasa.google.com/">Picassa</a>, to name a few.</p>

<p>Images should be properly optimized and linked back to your site. People can share and use your optimized image which gains exposure overall. Follow rules for upload, complying with terms and conditions.</p>

<p>Standards for image optimization:</p>

<ul>
<li>Always include \'Alt Text\' which tells the search engines what the picture is about. Adding source code to HTML is relatively easy and works like
this: &lt;img src=&quot;handmade-bracelets&quot; alt=&quot;Handmade
Bracelets.&quot;</li>
<li>Don\'t upload large hi-resolution files. File size impacts load times and is often prohibited in community sites.</li>
<li>Include a descriptive file name with your targeted keywords.</li>
<li>Your social media promotion should direct customers to your image. Text might be something like, &quot;Check out our new team photos.&quot; Shorten the link from a provider such as bit.ly that goes directly to your website and the image.</li>
</ul>',
			'effort'      => '30',
			'impact'      => '5',
			'weight'      => '1',
		),
		'233' => array(
			'title'       => '<p>Take pictures of yourself and your staff (either one-by-one or the whole team together), post them to your site and promote via social media.</p>',
			'description' => '<p>Images and video provide a great inbound marketing opportunity for your business. Have you ever looked at Google Images to find what you\'re looking for? Logos, team members, and products all have value to the search engines and your customers. Be sure to optimize them properly so they can easily be found.</p>',
			'howTo'       => '<p>Images should be properly optimized on your site. Consider adding them to an About Us page.</p>

<p>Standards for image optimization:</p>

<ul>
<li>Always include \'Alt Text\' which tells the search engines what the picture is about. Adding source code to HTML is relatively easy and works like
this: &lt;img src=&quot;handmade-bracelets&quot; alt=&quot;Handmade
Bracelets.&quot;</li>
<li>Don\'t upload large hi-resolution files. File size impacts load times and is often prohibited in community sites.</li>
<li>Include a descriptive file name with your targeted keywords.</li>
<li>Your social media promotion should direct customers to your image. Text might be something like, &quot;Check out our new team photos.&quot; Shorten the link from a provider such as bit.ly that goes directly to your website and the image.</li>
</ul>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '2',
		),
		'234' => array(
			'title'       => '<p>Take pictures of your product/service (if relevant), post them to your site and promote via social media.</p>',
			'description' => '<p>Images and video provide a great inbound marketing opportunity for your business. Have you ever looked at Google Images to find what you\'re looking for? Logos, team members, and products all have value to the search engines and your customers. Be sure to optimize them properly so they can easily be found.</p>',
			'howTo'       => '<p>Images should be properly optimized on your site. Consider adding them to a Products Page.</p>

<p>Standards for image optimization:</p>

<ul>
<li>Always include \'Alt Text\' which tells the search engines what the picture is about. Adding source code to HTML is relatively easy and works like
this: &lt;img src=&quot;handmade-bracelets&quot; alt=&quot;Handmade Bracelets.&quot;</li>
<li>Don\'t upload large hi-resolution files. File size impacts load times and is often prohibited in community sites.</li>
<li>Include a descriptive file name with your targeted keywords.</li>
</ul>

<p>Your social media promotion should direct customers to your image. Text might be something like, &quot;Check out our new products and share them with friends.&quot; Shorten the link from a provider like <a href="https://bitly.com">bit.ly</a> that goes directly to your website and the image.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '3',
		),
		'235' => array(
			'title'       => '<p>Check images websites to view what types of images, relevant to your industry, are becoming popular.</p>',
			'description' => '<p>Images and video provide a great inbound marketing opportunity for your business. Have you ever looked at Google Images to find what you\'re looking for? Logos, team members, and products all have value to the search engines and your customers. Be sure to optimize them properly so they can easily be found.</p>',
			'howTo'       => '<p>Look to image banks like <a href="http://www.flickr.com">Flickr</a> or <a href="http://imgur.com/">Imgur</a> to find non-copyrighted images that are currently trending in your industry. Create a media library for upcoming posts and web pages. Upload images as instructed by your CMS.</p>

<p>Standards for image optimization:</p>

<ul>
<li>Always include \'Alt Text\' which tells the search engines what the picture is about. Adding source code to HTML is relatively easy and works like
this: &lt;img src=&quot;handmade-bracelets&quot; alt=&quot;Handmade Bracelets.&quot;</li>
<li>Don\'t upload large hi-resolution files. File size impacts load times and is often prohibited in community sites.</li>
<li>Include a descriptive file name with your targeted keywords.</li>
</ul>',
			'effort'      => '90',
			'impact'      => '5',
			'weight'      => '4',
		),
		'236' => array(
			'title'       => '<p>Make a photo timeline that describes how your business developed, how your product/service changed, how the environment in your locale changed, etc. Post your site and promote via social media.</p>',
			'description' => '<p>Images and video provide a great inbound marketing opportunity for your business. Have you ever looked at Google Images to find what you\'re looking for? Logos, team members, and products all have value to the search engines and your customers. Be sure to optimize them properly so they can easily be found.</p>',
			'howTo'       => '<p>Look to an internal design team or find online editing and creation tools such as <a href="http://www.shutterfly.com">Shutterfly</a> or <a href="http://picasa.google.com/">Picassa</a>. This step can make the image compilation easier.</p>

<p>Images should be properly optimized and linked back to your site. People can share and use your optimized image which gains exposure overall. </p>

<p>Standards for image optimization:</p>

<ul>
<li>Always include \'Alt Text\' which tells the search engines what the picture is about. Adding source code to HTML is relatively easy and works like
this: &lt;img src=&quot;handmade-bracelets&quot; alt=&quot;Handmade
Bracelets.&quot;</li>
<li>Don\'t upload large hi-resolution files. File size impacts load times and is often prohibited in community sites.</li>
<li>Include a descriptive file name with your targeted keywords.</li>
</ul>

<p>Your social media promotion should direct customers to your image. Text might be something like, &quot;Wow, check out how much our company and our community has changed over the years. Our new timeline provides a flashback in time! 
Include a shortened link to the post by using a shortening tool like <a href="https://bitly.com">bit.ly</a> that goes directly to your website and the image.</p>',
			'effort'      => '120',
			'impact'      => '5',
			'weight'      => '5',
		),
		'237' => array(
			'title'       => '<p>Have professional photos of your business taken by Google Professional Photographers, post it to your site and promote via social media.</p>',
			'description' => '<p>Images and video provide a great inbound marketing opportunity for your business. Have you ever looked at Google Images to find what you\'re looking for? Logos, team members, and products all have value to the search engines and your customers. Be sure to optimize them properly so they can easily be found.</p>',
			'howTo'       => '<p>Consider sites where people commonly post images and where potential customers could be found, such as <a href="http://www.flickr.com">Flickr</a>, <a href="http://imgur.com/">Imgur</a>, <a href="http://instagram.com/">Instagram</a>, and <a href="http://picasa.google.com/">Picassa</a>.</p>

<p>Images should be properly optimized and linked back to your site. People can share and use your optimized image which gains exposure overall. Follow rules for upload, complying with terms and conditions. </p>

<p>Standards for image optimization:</p>

<ul>
<li>Always include \'Alt Text\' which tells the search engines what the picture is about. Adding source code to HTML is relatively easy and works like
this: &lt;img src=&quot;handmade-bracelets&quot; alt=&quot;Handmade
Bracelets.&quot;</li>
<li>Don\'t upload large hi-resolution files. File size impacts load times and is often prohibited in community sites.</li>
<li>Include a descriptive file name with your targeted keywords.</li>
</ul>',
			'effort'      => '120',
			'impact'      => '5',
			'weight'      => '6',
		),
		'238' => array(
			'title'       => '<p>Take pictures of your customers using your product/service, post them to your site and promote via social media.</p>',
			'description' => '<p>Images and video provide a great inbound marketing opportunity for your business. Have you ever looked at Google Images to find what you\'re looking for? Logos, team members, products all have value to the search engines and your customers. Be sure to optimize them properly so they can easily be found.</p>',
			'howTo'       => '<p>Capturing and sharing customer images is a great way to engage with customers. Be sure to have permission to share.</p>

<p>Images should be properly optimized and linked back to your site. People can share and use your optimized image which gains exposure overall. Follow rules for upload, complying with terms and conditions. </p>

<p>Standards for image optimization:</p>

<ul>
<li>Always include \'Alt Text\' which tells the search engines what the picture is about. Adding source code to HTML is relatively easy and works like
this: &lt;img src=&quot;handmade-bracelets&quot; alt=&quot;Handmade
Bracelets.&quot;</li>
<li>Don\'t upload large hi-resolution files. File size impacts load times and is often prohibited in community sites.</li>
<li>Include a descriptive file name with your targeted keywords.</li>
</ul>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '7',
		),
		'239' => array(
			'title'       => '<p>Take pictures of your participation in an event (seminar, conference, trade show, etc.), post to your site and promote via social media.</p>',
			'description' => '<p>Images and video provide a great inbound marketing opportunity for your business. Have you ever looked at Google Images to find what you\'re looking for? Logos, team members, and products all have value to the search engines and your customers. Be sure to optimize them properly so they can easily be found.</p>',
			'howTo'       => '<p>Consider sites where people commonly post images and where potential customers could be found, such as <a href="http://www.flickr.com">Flickr</a>, <a href="http://imgur.com/">Imgur</a>, <a href="http://instagram.com/">Instagram</a>, and <a href="http://picasa.google.com/">Picassa</a>. </p>

<p>Images should be properly optimized and linked back to your site. People can share and use your optimized image which gains exposure overall. Follow rules for upload, complying with terms and conditions. </p>

<p>Standards for image optimization:</p>

<ul>
<li>Always include \'Alt Text\' which tells the search engines what the picture is about. Adding source code to HTML is relatively easy and works like
this: &lt;img src=&quot;handmade-bracelets&quot; alt=&quot;Handmade Bracelets.&quot;</li>
<li>Don\'t upload large hi-resolution files. File size impacts load times and is often prohibited in community sites.</li>
<li>Include a descriptive file name with your targeted keywords.</li>
</ul>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '8',
		),
	),
	'119' => array(
		'240' => array(
			'title'       => '<p>Comment and/or share the following post via your social media profiles: .</p>',
			'description' => '',
			'howTo'       => '',
			'effort'      => '15',
			'impact'      => '2',
			'weight'      => '1',
		),
	),
	'120' => array(
		'241' => array(
			'title'       => '<p>Check for common questions asked in forums relevant to your niche, and answer them in your own blog posts.</p>',
			'description' => '<p>Blogging and content development is not just fuel for the search engines; it also positions you as an authority in your field. By staying on-top of trends, questions, and issues in your local area and across the nation, you become the \'go-to source\' for information. This adds credibility to your company overall plus generates additional business connections and sales leads.</p>',
			'howTo'       => '<p>Add industry blogs and sites that you want to monitor to an RSS feed?Google Feedburner is a well-known option. If you have a Google account,
you can access it <a href="https://feedburner.google.com">here</a>.</p>

<p>Set up Google alerts around topics of expertise. Write a post on your blog and link to it from your answer on the forum.</p>

<p>A rule of thumb for forums and community is 80% participation and 20% self-promotion. Be respectful, and you\'ll be welcome!</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '1',
		),
		'242' => array(
			'title'       => '<p>Conduct a survey via your site, post the results on your blog and promote via social media.</p>',
			'description' => '<p>Blogging and content development is not just fuel for the search engines, it also can position you as an authority in your field. By asking customers, via website surveys, topics of interest in your industry, learn more about what matters and can address it in your blog. This adds credibility to your company overall.</p>',
			'howTo'       => '<p>Learn more about your customers by running a brief survey on your site. SurveyMonkey is a great option, you can you can learn more about it <a href="https://surveymonkey.com">here</a>. Your social media promotion should direct customers to your post.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '10',
		),
		'243' => array(
			'title'       => '<p>Write a blog article regarding your opinion on a local newspaper article, and promote it via social media.</p>',
			'description' => '<p>Blogging and content development is not just fuel for the search engines; it also positions you as an authority in your field. By staying on-top of news in your local area, you can write an informed opinion about the topic on your blog. This adds credibility to your company overall plus generates additional business connections and sales leads.</p>',
			'howTo'       => '<p>Find a local news item of interest. Write a post around it and hyperlink to more information on your site if applicable. Your social media promotion should direct customers to your post.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '11',
		),
		'244' => array(
			'title'       => '<p>Send a story or tip for your business to your local newspaper.</p>',
			'description' => '<p>Take your blogging expertise and leverage your knowledge in print as well. Create and send your local paper an article about news in the area. If you can get published in print, you can promote it on your blog. This adds credibility to your company overall plus generates greater business exposure.</p>',
			'howTo'       => '<p>Be sure to send your news to the appropriate person at the paper. If the article gets published, write a blog post around that for additional coverage, and be sure to promote it via social media.</p>',
			'effort'      => '60',
			'impact'      => '15',
			'weight'      => '12',
		),
		'245' => array(
			'title'       => '<p>Send a story or tip for your business to your local Patch.com.</p>',
			'description' => '<p>Blogging and content development is not just fuel for the search engines; it also positions you as an authority in your field. Stay on-top of news in your local area and send an article to your local Patch.com. This adds credibility to your company overall plus generates additional business connections and sales leads.</p>',
			'howTo'       => '<p>Go to Patch.com to find your local chapter. Follow submission guidelines. If the article gets published, write a blog post around that for additional coverage, and be sure to promote it via social media.</p>',
			'effort'      => '60',
			'impact'      => '15',
			'weight'      => '13',
		),
		'246' => array(
			'title'       => '<p>Sign up for HARO as a &quot;Source,&quot; and look for potential opportunities for guest blogging.</p>',
			'description' => '<p>Haro is a guest blogging/reporting site that allows people to contribute on their topic of expertise. Guest blogging allows you to gain authority and links by contributing to popular blogs.</p>',
			'howTo'       => '<p>Sign up for Haro <a href="http://www.helpareporter.com/sources">here</a>.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '14',
		),
		'247' => array(
			'title'       => '<p>Sign up for Blogger LinkUp, and look for potential opportunities for guest blogging.</p>',
			'description' => '<p>Blogger LinkUp is a guest blogging site that allows people to contribute on their topic of expertise. Guest blogging allows you to gain authority and links by contributing to popular blogs.</p>',
			'howTo'       => '<p>Sign up for Blogger Linkup <a href="http://www.bloggerlinkup.com/guest-post-offer">here</a>.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '15',
		),
		'248' => array(
			'title'       => '<p>Sign up for My Blog Guest, and look for potential opportunities for guest blogging.</p>',
			'description' => '<p><a href="http://myblogguest.com">My Guest Blog</a> is a guest blogging site that allows people to contribute on their topic of expertise. Guest blogging allows you to gain authority and links by contributing to popular blogs.</p>',
			'howTo'       => '<p>Learn more about My Guest Blog <a href="http://myblogguest.com">here</a>.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '16',
		),
		'249' => array(
			'title'       => '<p>Write and distribute a press release to relevant sites.</p>',
			'description' => '<p>A good vehicle for small businesses to gain exposure and traffic is to release press releases to the public. Distribution of at least one news article will help to increase your company\'s visibility to the public. </p>',
			'howTo'       => '<p>Below are some optimization tips on how to create a press release for your small business:</p>

<ul>
<li>Browse some general examples of press releases before writing one yourself. For example, Google posts press releases that are determined as &quot;high-quality&quot; in its news listings.</li>
<li>Browse some press releases in Google that are related to your site, such as in the same category or industry. Then, look through some press releases with similar keywords to your own.</li>
<li>Don\'t rush. Be sure to spend time compiling a high-quality article pertaining to your site.</li>
<li>You may notice that if your article relates to a very niche location or subject, the article is more likely to appear high in search results.</li>
<li>Whether you are writing about a new product or news announcement, make sure the article links back to your site at least once, it is important to add at least one hyperlink to your site in the article.</li>
<li>PRWeb offers tips on writing new releases here: <a href="http://www.prweb.com/pr/press-release-tip/index.html">PRWeb Tips</a>. This site is a popular digital distribution network that links to news releases and press releases. <a href="http://www.prweb.com">PRWeb</a> covers submission and distribution for small and large businesses.</li>
</ul>',
			'effort'      => '120',
			'impact'      => '2',
			'weight'      => '17',
		),
		'250' => array(
			'title'       => '<p>Check for common questions asked on Facebook and answer them in your own blog posts. Follow groups that talk about topics relevant to your specialty.</p>',
			'description' => '<p>Blogging and content development is not just fuel for the search engines; it also positions you as an authority in your field. By staying on-top of trends, questions, and issues in your local area and across the nation, you become the \'go-to source\' for information.</p>

<p> This adds credibility to your company overall plus generates additional business connections and sales leads.</p>',
			'howTo'       => '<p>Sign up for relevant groups in your industry on Facebook, and monitor the conversation. A great place to start is with Facebook Questions, which you can learn more about <a href="http://www.facebook.com/help/182071178590498">here</a>.</p>

<p>If a question comes up in your area of business, create a blog post on the
topic then link back to it in your answer on Facebook.</p>

<p>A rule of thumb for forums and community is 80% participation and 20% self-promotion. Be respectful, and you\'ll be welcome!</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '2',
		),
		'251' => array(
			'title'       => '<p>See what other sites/blogs in your industry are writing about, and publish articles on similar topics.</p>',
			'description' => '<p>Blogging and content development is not just fuel for the search engines; it also positions you as an authority in your field. By staying on-top of trends, questions, and issues in your local area and across the nation, you become the \'go-to source\' for information. This adds credibility to your company overall plus generates additional business connections and sales leads.</p>',
			'howTo'       => '<p>Add industry blogs and sites that you want to monitor to an RSS feed. Google Feedburner is a well-known option. If you have a Google account,
you can access it <a href="https://feedburner.google.com">here</a>. </p>

<p>Technorati is a blog monitoring tool and provides data/URLs for the top blogs in your industry. Learn about it <a href="http://technorati.com/blogs/top100/.">here.</a></p>

<p>Follow the industry leader\'s example and write posts on similar topics. A rule of thumb for forums and community is 80% participation and 20% self-promotion. Be respectful, and you\'ll be welcome!</p>',
			'effort'      => '90',
			'impact'      => '5',
			'weight'      => '3',
		),
		'252' => array(
			'title'       => '<p>See what people discuss about in the comment sections of other blogs in your industry, and write articles around those topics.</p>',
			'description' => '<p>Blogging and content development is not just fuel for the search engines; it also positions you as an authority in your field. By staying on-top of trends, questions, and issues in your local area and across the nation, you become the \'go-to source\' for information. This adds credibility to your company overall plus generates additional business connections and sales leads.</p>',
			'howTo'       => '<p>Technorati is a blog monitoring tool and provides data/URLs for the top blogs in your industry. Learn about it <a href="http://technorati.com/blogs/top100/.">here.</a>
Follow the industry leader\'s example and write posts on similar topics.</p>

<p>A rule of thumb for forums and community is 80% participation and 20% self-promotion. Be respectful and you\'ll be welcome!</p>',
			'effort'      => '30',
			'impact'      => '5',
			'weight'      => '4',
		),
		'253' => array(
			'title'       => '<p>Check what people talk about on Twitter, and write articles around those topics.</p>',
			'description' => '<p>Blogging and content development is not just fuel for the search engines; it also positions you as an authority in your field. By staying on-top of what\'s trending on Twitter in your local area and across the nation, you get immediate news/story material you can blog about. This adds credibility to your company overall plus generates additional business connections and sales leads.</p>',
			'howTo'       => '<p>There are many Twitter monitoring tools and methods. A good start is <a href="http://www.tweetdeck.com">TweetDeck</a>, which allows you to see conversation and mentions on multiple streams. Once you find a trending topic, or one you are qualified to address, write a blog post and promote via social medial channels to increase your online exposure.</p>

<p>A rule of thumb for forums and community is 80% participation and 20% self-promotion. Be respectful, and you\'ll be welcome!</p>',
			'effort'      => '90',
			'impact'      => '5',
			'weight'      => '5',
		),
		'254' => array(
			'title'       => '<p>Check what the most common questions on Q&amp;A sites are and answer them on your blog.</p>',
			'description' => '<p>Blogging and content development is not just fuel for the search engines, it also can position you as an authority in your field. By staying on-top of Q&amp;A sites with relevant questions and issues in your industry, you get current information that you can blog about on your own. </p>

<p>This adds credibility to your company overall plus generates additional business connections and sales leads.</p>',
			'howTo'       => '<p>Add Q&amp;A sites that you want to monitor to an RSS feed?Google Feedburner is a well-known option. If you have a Google account,
you can access it <a href="https://feedburner.google.com">here</a>.</p>

<p>Once you find a recurring question, or those you are qualified to address, write a blog post and promote via social medial channels to increase your online exposure.</p>

<p>A rule of thumb for forums and community is 80% participation and 20% self-promotion. Be respectful and you\'ll be welcome!</p>',
			'effort'      => '90',
			'impact'      => '5',
			'weight'      => '6',
		),
		'255' => array(
			'title'       => '<p>Review your Google Alerts summary, and write a blog post summarizing what type of content is being shared often.</p>',
			'description' => '<p>If you have set up Google Alerts for your business (which you should have done as part of an earlier course), look at content being shared and complete a post on that information.</p>',
			'howTo'       => '<p>Click <a href="http://www.google.com/alerts">here</a> to update and/or set-up Google Alerts for your business.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '7',
		),
		'256' => array(
			'title'       => '<p>Write a blog article that answers questions often asked in the industry forums and/or around social media, and promote it via social media.</p>',
			'description' => '<p>Blogging and content development is not just fuel for the search engines; it also can position you as an authority in your field. By staying on-top of Q&amp;A sites with relevant questions and issues in your industry, you get current information that you can blog about on your own. This adds credibility to your company overall, and it generates additional business connections and sales leads.</p>',
			'howTo'       => '<p>Add industry blogs and sites that you want to monitor to an RSS feed?Google Feedburner is a well-known option.? If you have a Google account,
you can access it <a href="https://feedburner.google.com">here</a>. </p>

<p>Technorati is a blog monitoring tool and provides data/URLs for the top blogs in your industry. Learn about it <a href="http://technorati.com/blogs/top100/.">here.</a>
Follow the industry leader\'s example and write posts on similar topics.</p>

<p>A rule of thumb for forums and community is 80% participation and 20% self-promotion. Be respectful, and you\'ll be welcome! Your social media promotion should direct customers to your post.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '8',
		),
		'257' => array(
			'title'       => '<p>Use Google Analytics to find search terms your customers are using, and write content around it.</p>',
			'description' => '<p>Having <a href="http://www.google.com/analytics/">Google Analytics</a> on your site provides great insight into customer behavior. Using Google Analytics, you can review your website\'s visitors popular searches and develop content around those items.</p>',
			'howTo'       => '<p>Get started with <a href="http://www.google.com/analytics/">Google Analytics</a> today.</p>',
			'effort'      => '90',
			'impact'      => '5',
			'weight'      => '9',
		),
	),
	'121' => array(
		'258' => array(
			'title'       => '<p>Create a video presenting an overview of your business and/or each of your products/services.</p>',
			'description' => '<p>Video can have a huge impact on brand awareness, traffic to your site, and customer engagement. Video included in almost any channel of your website, email, blogs, etc. improves conversion, increases time on site and click-thru rate on newsletters. Any new video you create should always be promoted via social media driving customers back to your site.                                                                                                                    </p>',
			'howTo'       => '<p>An easy way to create product videos is through a 3rd party provider. <a href="http://www.getbravo.com">GetBravo</a> is a great option. Learn more about GetBravo <a href="http://www.getbravo.com">here</a>.</p>

<p>For all social media efforts, the video content should link back to your site improving your online exposure and making it easy for customers to find you.</p>',
			'effort'      => '120',
			'impact'      => '5',
			'weight'      => '1',
		),
		'259' => array(
			'title'       => '<p>Create video testimonials by your customers, post them to your site and promote via social media.</p>',
			'description' => '<p>Video can have a huge impact on brand awareness, traffic to your site, and customer engagement. Video included in almost any channel of your website, email, blogs, etc. improves conversion, increases time on site and click-thru rate on newsletters. Any new video you create should always be promoted via social media driving customers back to your site.                                                                                                                    </p>',
			'howTo'       => '<p>An easy way to create user-generated videos is through a 3rd party provider. GetBravo is a great option. Through GetBravo, customers create a video review from their computer and upload it to their own website and other social media channels. Learn more about GetBravo <a href="http://www.getbravo.com/">here</a>.</p>

<p>For all social media efforts, the video content should link back to your site improving your online exposure and making it easy for customers to find you.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '2',
		),
		'260' => array(
			'title'       => '<p>Create a video of you and/or your staff working on a project or crafting a product, post them to your site and promote via social media.</p>',
			'description' => '<p>Video can have a huge impact on brand awareness, traffic to your site, and customer engagement. Video included in almost any channel?your website, email, blogs, etc. improves conversion, increases time on site and click-thru rate on newsletters. Any new video you create should always be promoted via social media driving customers back to your site.                                                                                                                    </p>',
			'howTo'       => '<p>An easy way to create user-generated videos is through a 3rd party provider. GetBravo is a great option. Learn more about GetBravo <a href="http://www.getbravo.com">here</a>.  </p>

<p>Create a plan (storyboards) for your video so you know what shots are needed to properly share the process and product. </p>

<p>For all social media efforts, the video content should link back to your site improving your online exposure and making it easy for customers to find you.</p>',
			'effort'      => '120',
			'impact'      => '5',
			'weight'      => '3',
		),
		'261' => array(
			'title'       => '<p>Create a video highlighting the impact of your product/service and key differentiators from other products/services, post it to your site and promote via social media.</p>',
			'description' => '<p>Video can have a huge impact on brand awareness, traffic to your site, and customer engagement. Video included in almost any channel of your website, email, blogs, etc. improves conversion, increases time on site and click-thru rate on newsletters. Any new video you create should always be promoted via social media driving customers back to your site.                                                                                                                    </p>',
			'howTo'       => '<p>An easy way to create user-generated videos is through a 3rd party provider. GetBravo is a great option. Learn more about GetBravo <a href="http://www.getbravo.com/">here</a>.</p>

<p>If your product has a unique story, consider creating an &quot;explainer video,&quot; which pairs animation and words for greater impact. There are many great companies, and SwitchVideo is one. Learn more about SwitchVideo <a href="http://www.switchvideo.com/">here</a>.</p>

<p>For all social media efforts, the video content should link back to your site improving your online exposure and making it easy for customers to find you
<a href="http://www.switchvideo.com/">here</a>.</p>',
			'effort'      => '120',
			'impact'      => '5',
			'weight'      => '4',
		),
		'262' => array(
			'title'       => '<p>Make a video wish (by yourself or with your staff) for some popular holiday event ? Christmas, New Year, Easter, Halloween. Post it to your site and promote via social media.</p>',
			'description' => '<p>Video can have a huge impact on brand awareness, traffic to your site, and customer engagement. Video included in almost any channel: your website, email, blogs, etc. improves conversion, increases time on site and click-thru rate on newsletters. Any new video you create should always be promoted via social media driving customers back to your site.                                                                                                                    </p>',
			'howTo'       => '<p>Putting a personal face on your company engages customers and creates connections. Don\'t over-produce of feel your video needs to be perfect. An easy way to create user-generated videos is through a 3rd party provider. </p>

<p><a href="http://www.getbravo.com/">GetBravo</a> is a great option. For all social media efforts, the video content should link back to your site improving your online exposure and making it easy for customers to find you.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '5',
		),
		'263' => array(
			'title'       => '<p>Create a professionally developed video leveraging local videographers via Lightswitch, post it to your site and promote via social media.</p>',
			'description' => '<p>Video can have a huge impact on brand awareness, traffic to your site, and customer engagement. Video included in almost any channel of your website, email, blogs, etc. improves conversion, increases time on site and click-thru rate on newsletters. Any new video you create should always be promoted via social media driving customers back to your site.                                                                                                                    </p>',
			'howTo'       => '<p>Take advantage of professional local videographers to really make your company shine. LightSwitch is a great option. Learn more about LightSwitch <a href="http://www.lightswitch.com//">here</a>.</p>

<p>For all social media efforts, the video content should link back to your site improving your online exposure and making it easy for customers to find you.</p>',
			'effort'      => '120',
			'impact'      => '5',
			'weight'      => '6',
		),
		'264' => array(
			'title'       => '<p>Explore YouTube to see what kind of videos, relevant to your industry, are becoming popular.</p>',
			'description' => '<p><a href="http://youtube.com">YouTube</a> offers the world of video on every imaginable topic. Using Youtube, you can search keywords and explore what leaders in your industry are posting videos on.</p>',
			'howTo'       => '<p>Explore and sign up for YouTube <a href="http://youtube.com/">here</a>.</p>',
			'effort'      => '90',
			'impact'      => '5',
			'weight'      => '7',
		),
	),
	'124' => array(
		'265' => array(
			'title'       => '<p>Add a contact form on your website.</p>',
			'description' => '<p>If customers are going to subscribe, purchase or contact you, you need to make it easy for them to do so. A \'Contact Form\' is convenient for them to reach out and for you to measure. </p>',
			'howTo'       => '<p>Most CMS systems like WordPress allow you to create an online contact form with the dashboard. Follow instructions as outlined.</p>

<p>If you aren\'t using a standard CMS or have more of a custom site, consider adding a 3rd party web form builder. Email Me Form offers free sign up with details found <a href="http://www.emailmeform.com/">here</a>.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '1',
		),
		'266' => array(
			'title'       => '<p>Add symbols of trust to your website.</p>',
			'description' => '<p>If customers are going to subscribe, purchase, or share information, they need to know that you a reputable and trustworthy company. Make them as comfortable as possible by incorporating &quot;Trust Symbols&quot; to your website.</p>',
			'howTo'       => '<p>Look at competitor sites and your own online shopping behavior to determine what \'symbols\' make you feel better about submitting personal information and buying online. Better Business Bureau? Verisign? Paypal? Be sure to include the logos of these companies to put your customers at ease. A good article on &quot;symbols of trust&quot; can be found <a href="http://unbounce.com/conversion-rate-optimization/trust-symbols-for-new-website-conversions/">here</a>.</p>',
			'effort'      => '30',
			'impact'      => '5',
			'weight'      => '2',
		),
		'267' => array(
			'title'       => '<p>Add a Terms and Conditions page to your website.</p>',
			'description' => '<p>If customers are going to subscribe, purchase, or share information,they need to know that you a reputable and trustworthy company. Make them as comfortable as possible by incorporating a clearly defined \'Terms and Conditions Page\' to your website.</p>',
			'howTo'       => '<p>Look at competitor sites and your offline terms to draft your Terms and Conditions. They don\'t have to be long and complicated (unless you run a big business with heavy legal implications), there are often templates to follow. A great article on creating Terms and Conditions found <a href="http://smallbusiness.chron.com/create-terms-conditions-website-41937.html">here</a>. </p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '3',
		),
		'268' => array(
			'title'       => '<p>Add a Guarantees page to your website.</p>',
			'description' => '<p>If customers are going to subscribe, purchase, or share information,they need to know that you a reputable and trustworthy company. Make them as comfortable as possible by incorporating a Guarantees\' page\' to your website.</p>',
			'howTo'       => '<p>Make the guarantee associated with your product or service easy to find on your website. Consider adding a page in the global footer or as a promotional sidebar item if particularly compelling. Adding a strong guarantee can improve conversion especially if it overcomes buying obstacles (i.e. easy returns, etc.). Read about the importance of guarantees <a href="http://www.copyblogger.com/strong-guarantee/">here</a>.</p>',
			'effort'      => '30',
			'impact'      => '5',
			'weight'      => '4',
		),
		'269' => array(
			'title'       => '<p>Add a Warranties page to your website.</p>',
			'description' => '<p>If customers are going to subscribe, purchase, or share information, they need to know that you a reputable and trustworthy company. Make them as comfortable as possible by incorporating a \'Warranties\' page\' to your website.</p>',
			'howTo'       => '<p>Make the warranty associated with your product or service easy to find on your website. Consider adding a page in the global footer or as a promotional sidebar item if particularly compelling. Adding a strong warranty can improve conversion especially if it overcomes buying obstacles (i.e. all repairs completed for five years, etc.).</p>',
			'effort'      => '30',
			'impact'      => '5',
			'weight'      => '5',
		),
		'270' => array(
			'title'       => '<p>Set up specific goals using Google Analytics.</p>',
			'description' => '<p>Google Analytics allows you to set specific goals and track their conversion. Four main kinds of conversion are: RL Destination (a specific web page); Visit Duration; Page/Visit (for web) and Screens/Visit (for apps); Event. Tracking these goals show which of your online goals is performing and how you might adjust the conversion funnel for greater conversion.</p>',
			'howTo'       => '<p>Learn how to set up Google Analytics Goals <a href="http://support.google.com/analytics/bin/answer.py?hl=en-GB&amp;answer=1032415">here</a>.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '6',
		),
		'271' => array(
			'title'       => '<p>Add live chat to your website.</p>',
			'description' => '<p>Live chat allows customers real time Q&amp;A with a representative. This allows immediate answers and may stop a customer from leaving your site because they can\'t see what they are looking for.</p>',
			'howTo'       => '<p>There are many 3rd party providers of live chat services. LiveChat offers a free trial. Learn more <a href="https://www.livechatinc.com/signup/">here</a>.</p>',
			'effort'      => '30',
			'impact'      => '2',
			'weight'      => '7',
		),
		'272' => array(
			'title'       => '<p>Test different call-to-actions.</p>',
			'description' => '<p>A call-to-action (CTA) is telling a customer exactly what you want them to do. This can be something like: Order today; sign-up for our RSS; download a whitepaper, etc. </p>

<p>Be clear and always ask for the close, don\'t assume they know what you want them to do next.</p>',
			'howTo'       => '<p>The call-to-action is one of the single most important elements on your page and in testing conversion. You can adjust message, graphics, offers and see what is more likely to convert. For more information about testing call-to-actions can be found <a href="http://blog.crazyegg.com/2012/07/18/test-call-to-action-buttons/">here</a>.</p>',
			'effort'      => '60',
			'impact'      => '10',
			'weight'      => '8',
		),
	),
	'127' => array(
		'273' => array(
			'title'       => '<p>Start collecting your customers\' emails.</p>',
			'description' => '<p>It\'s important to capture email addresses from online transactions and across every touch point of your marketing campaigns. This allows you to effectively communicate with them be it special offers, order tracking, or industry news.</p>',
			'howTo'       => '<p>Collecting emails and managing the contact database is a critical part of your business. Many 3rd party providers allow you to collect, store, and sort your customer\'s data. Research the best option for your business online. AWeber is a leading provider of subscriber management. Find out more about AWeber <a href="http://www.aweber.com/subscriber-management.htm">here</a>.</p>',
			'effort'      => '30',
			'impact'      => '20',
			'weight'      => '1',
		),
		'274' => array(
			'title'       => '<p>Offer special offers to your social media followers only.</p>',
			'description' => '<p>Communicating regularly with customers, such as special offers, monthly newsletters, seasonal wishes, order tracking, and sharing industry/company news, is a key part of driving more traffic to your site. Capturing customer data, and using it effectively, provides tremendous marketing opportunities that no business owner should overlook.</p>',
			'howTo'       => '<p>Want someone to \'like\' you, \'follow\' you, or \'mention\' you online? Then you should provide special incentives to those that do. Select an email provider that integrates social media options so you can easily track responses. Always honor and follow-up on any promise you have made.</p>',
			'effort'      => '15',
			'impact'      => '10',
			'weight'      => '10',
		),
		'275' => array(
			'title'       => '<p>Share information about new products/services that you offer via your social media profiles.</p>',
			'description' => '<p>Communicating regularly with customers is a key part of driving more traffic to your site. Examples of what you can communicate include special offers, monthly newsletters, seasonal wishes, order tracking, and sharing industry/company news. Capturing customer data and using it effectively provides tremendous marketing opportunities that no business owner should overlook.</p>',
			'howTo'       => '<p>Never neglect to share information about new products or services. Provide links making it easy to share with friends on major social media platforms.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '11',
		),
		'276' => array(
			'title'       => '<p>Set up and send out a monthly newsletter.</p>',
			'description' => '<p>Communicating regularly with customers\' special offers, monthly newsletters, seasonal wishes, order tracking, sharing industry/company news is a key part of driving more traffic to your site. Capturing customer data and using it effectively provides tremendous marketing opportunities that no business owner should overlook.</p>',
			'howTo'       => '<p>There are many 3rd party email providers, so choose one that works well with your website and online marketing communications. A few popular options are MailChimp, Constant Contact, Vertical Response, and Campaign Monitor. Do your research and find out what others in your industry use and what integrates best with your web platform.</p>

<p>Committing to regular communications, and consistency is key. Set a regular publishing schedule and determine different areas you want to publish on each month. Perhaps it might be:</p>

<ul>
<li>feature article</li>
<li>monthly promotion</li>
<li>supplier highlight</li>
<li>customer spotlight</li>
<li>bestselling product</li>
<li>fun fact or tip</li>
<li>Once standard blocks are determined, it becomes easy to slot in new content each month. Track all conversions so you are able to determine what provides the greatest ROI.</li>
</ul>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '2',
		),
		'277' => array(
			'title'       => '<p>Set your email signature with WiseStamp.</p>',
			'description' => '<p><a href="http://www.wisestamp.com">WiseStamp</a> is a great application that allows you to create a custom email signature that contains everything you do online. Communicating regularly with customers is a key part of driving more traffic to your site. Capturing customer data and using it effectively provides tremendous marketing opportunities that no business owner should overlook.</p>',
			'howTo'       => '<p>From basic contact information to feeds of your social media to inspirational quotes, WiseStamp lets all of your customers see what you are doing from the quick email that you sent. To learn more about WiseStamp, <a href="http://www.wisestamp.com">check here</a>.</p>',
			'effort'      => '15',
			'impact'      => '1',
			'weight'      => '3',
		),
		'278' => array(
			'title'       => '<p>Send emails with holiday discounts to your customers.</p>',
			'description' => '<p>ommunicating regularly with customers is a key part of driving more traffic to your site. Capturing customer data and using it effectively provides tremendous marketing opportunities that no business owner should overlook.</p>',
			'howTo'       => '<p>Creating and sending out personalized email to customers is always best. Your email platform should track holidays and send out scheduled best wishes or rewards to all customers.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '4',
		),
		'279' => array(
			'title'       => '<p>Send emails with birthday wishes and birthday discounts to your customers.</p>',
			'description' => '<p>Communicating regularly with customers is a key part of driving more traffic to your site. Capturing customer data and using it effectively provides tremendous marketing opportunities that no business owner should overlook.</p>',
			'howTo'       => '<p>Creating and sending out personalized email to customers is always best. Your email platform should track customer milestones and provide best wishes or rewards to celebrate your customers\' special days.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '5',
		),
		'280' => array(
			'title'       => '<p>Leverage a loyal customers program.</p>',
			'description' => '<p>Communicating regularly with customers is a key part of driving more traffic to your site. Capturing customer data and using it effectively provides tremendous marketing opportunities that no business owner should overlook.</p>',
			'howTo'       => '<p>Loyalty programs reward existing customers. Never overlook them. There are many great online loyalty programs that you can add to your database. Learn more about them <a href="http://www.entrepreneur.com/article/224853">here</a>.</p>',
			'effort'      => '60',
			'impact'      => '5',
			'weight'      => '6',
		),
		'281' => array(
			'title'       => '<p>Notify your customers via email for events related to your business.</p>',
			'description' => '<p>Communicating regularly with customers is a key part of driving more traffic to your site. Capturing customer data and using it effectively provides tremendous marketing opportunities that no business owner should overlook.</p>',
			'howTo'       => '<p>Use your email program to notify customers of upcoming events. You can also offer a special reward to those customers who mention promotional codes from your email.</p>',
			'effort'      => '15',
			'impact'      => '5',
			'weight'      => '8',
		),
		'282' => array(
			'title'       => '<p>Send out customer satisfaction surveys and feedback questionnaires to your customers.</p>',
			'description' => '<p>Communicating regularly with customers is a key part of driving more traffic to your site. Capturing customer data and using it effectively provides tremendous marketing opportunities that no business owner should overlook.</p>',
			'howTo'       => '<p>On-going feedback from customers is critical. You can maximize online tools such as <a href="http://www.surveymonkey.com">SurveyMonkey</a> or other survey providers. Ask specific questions and keep the questions short to gain the maximum response. Measure and be prepared to post results online so that customers can check back for results.</p>',
			'effort'      => '60',
			'impact'      => '10',
			'weight'      => '9',
		),
	),
	'148' => array(
		'283' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '1',
		),
		'284' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '2',
		),
		'285' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '3',
		),
		'286' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '4',
		),
		'287' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '5',
		),
		'288' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '6',
		),
		'289' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '7',
		),
		'290' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '8',
		),
		'291' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '9',
		),
		'292' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '10',
		),
		'293' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '11',
		),
		'294' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '12',
		),
		'295' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '13',
		),
		'296' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '14',
		),
		'297' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '15',
		),
		'298' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '16',
		),
		'299' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '17',
		),
		'300' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '18',
		),
		'301' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '19',
		),
		'302' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '20',
		),
		'303' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '21',
		),
		'304' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '22',
		),
		'305' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '23',
		),
		'306' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '24',
		),
		'307' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '25',
		),
		'308' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '26',
		),
		'309' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '27',
		),
		'310' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '28',
		),
		'311' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '29',
		),
		'312' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '30',
		),
		'313' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '31',
		),
		'314' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '32',
		),
		'315' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '33',
		),
		'316' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '34',
		),
		'317' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '35',
		),
		'318' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '36',
		),
		'319' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '37',
		),
		'320' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '38',
		),
		'321' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '39',
		),
		'322' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '40',
		),
		'323' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '41',
		),
		'324' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '42',
		),
		'325' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '43',
		),
		'326' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '44',
		),
		'327' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '45',
		),
		'328' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '46',
		),
		'329' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '47',
		),
		'330' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '48',
		),
		'331' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '49',
		),
		'332' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '50',
		),
		'333' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '51',
		),
		'334' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '52',
		),
		'335' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '53',
		),
		'336' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '54',
		),
		'337' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '55',
		),
		'338' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '56',
		),
		'339' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '57',
		),
		'340' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '58',
		),
		'341' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '59',
		),
		'342' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '60',
		),
		'343' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '61',
		),
		'344' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '62',
		),
		'345' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '63',
		),
		'346' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '64',
		),
		'347' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '65',
		),
		'348' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '66',
		),
		'349' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '67',
		),
		'350' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '68',
		),
		'351' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '69',
		),
		'352' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '70',
		),
		'353' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '71',
		),
		'354' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '72',
		),
		'355' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '73',
		),
		'356' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '74',
		),
		'357' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '75',
		),
		'358' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '76',
		),
		'359' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '77',
		),
		'360' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '78',
		),
		'361' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '79',
		),
		'362' => array(
			'title'       => '<p>Optimize Page Content.</p>',
			'description' => '<p>It\'s important to optimize each page\'s content for maximum organic SEO impact.</p>',
			'howTo'       => '<p>Utilizing SEOPressor optimize the page. <strong>BE NATURAL!!</strong> Do not attempt to get a perfect score, this can end up penalizing you instead of helping you. Only do an item in SEOPressor if it makes sense for the current page. Not all pages need the keyword in bold, underline, and italics. Not every page needs a H2 or H3, only use them when breaking up page content.</p>',
			'effort'      => '60',
			'impact'      => '30',
			'weight'      => '80',
		),
	),
);
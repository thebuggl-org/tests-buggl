<?php

namespace Buggl\MainBundle\Helper;

class BugglConstant
{
    private $constants = null;

    public function __construct()
    {
        $this->constants = array(
            'draft_guide' => 0,
            'unpublished_guide' => 1,
            'published_guide' => 2,
            'archived_guide' => 3,
            'removed_guide' => 4,
            'featured_in_home' => 1,
			'featured_in_profile' => 1,
			'unfeatured_in_profile' => 0,
            'spotlight_guide_in_home' => 1,
            'featured_guide_in_country' => 1,
            'published_category' => 1,
            'unpublished_category' => 0,
            'admin_category' => 1,
            'custom_category' => 0,
            'super_admin' => 1,
            'admin' => 2,
            'admin_user_roles' => array(1 => 'ROLE_SUPER_ADMIN', 2 => 'ROLE_ADMIN'),

			// pagination limits
			'eguide_activities_dashboard_pagination' => 8,
			'local_references_pagination' => 10,
			'messages_pagination' => 10,
			'payments_pagination' => 10,
			'guide_request_pagination' => 10,
			'activities_dashboard' => 10,
			'beta_invite_pagination' => 10,

			// user contexts
			'SITE_ADMIN' => 0,
			'LOCAL_AUTHOR' => 1,

			'BUGGL_EMAIL' => "admin@buggl.com",
			'LOCAL_REF_BULK_MAIL' => 0,

			// local reference status
			'LOCAL_REF_PENDING' => 0,
			'LOCAL_REF_SENT' => 1,
			'LOCAL_REF_LIST' => 2,
			'LOCAL_REF_UNSENT' => 3,

            'unviewed_review' => 0,
            'approved_review' => 1,
            'denied_review' => 2,
            'rating_upper_limit' => 5,
            'rating_lower_limit' => 0,
            'allowed_user' => 0,
            'suspended_user' => 1,

			// message thread status
			'MSG_INBOX' => 0,
			'MSG_ARCHIVED' => 1,

			// message status
			'MSG_NEW' => 0,
			'MSG_UNREAD' => 1,
			'MSG_READ' => 2,

			// email verification status
			'VERIFICATION_PENDING' => 0,
			'VERIFICATION_APPROVED' => 1,
			'VERIFICATION_CANCELED' => 2,

			// email status
			'EMAIL_UNVERIFIED' => 0,
			'EMAIL_VERIFIED' => 1,

			// counts needed before being displayed in frontend
			'DISP_LIM_REFERENCE' => 1,
			'DISP_LIM_REVIEW' => 1,
			'DISP_LIM_DOWNLOAD' => 1,

            //
            'draft' => 0,
            'unpublished' => 10,
            'published' => 2,
            'featured' => 3,
            'archived' => 4,
            'denied' => 5,
            'suspended' => 6,
			'deleted' => 7,

            //buggl featured limit
            'featured_in_home_limit' => 13,
            'main_spotlight_limit' => 1,

            //filters
            'revenue_rank_eguide' => 0,
            'revenue_rank_location' => 1,

			// buggl activity types
			'ACTIVITY_CREATE_GUIDE' => 1,
			'ACTIVITY_PUBLISH_GUIDE' => 2,
			'ACTIVITY_PURCHASE_GUIDE' => 3,
			'ACTIVITY_FOLLOW_AUTHOR' => 4,
			'ACTIVITY_DOWNLOAD_GUIDE' => 5,
			'ACTIVITY_UPDATE_GUIDE' => 6,
			'ACTIVITY_DELETE_GUIDE' => 7,
			'ACTIVITY_UPDATE_GUIDE_PRICE' => 8,

            //country status
            'DEACTIVATED_COUNTRY' => 0,
            'REQUESTED_COUNTRY' => 1,
            'APPROVED_COUNTRY' => 2,

			//beta invite status
			'BETA_INVITE_PENDING' => 0,
			'BETA_INVITE_SENT' => 1,
			'BETA_INVITE_ACCEPTED' => 2,
			
			//share status
			'SHARE_PENDING' => 0,
			'SHARE_SENT' => 1,
			'SHARE_SUCCESS' => 2,

            'APPROVE_LOCAL_AUTHOR' => 1,

            'DENIED_TRAVEL_GUIDE_SUBJECT' => 'Your Buggl Guide needs work',
            'APPROVED_TRAVEL_GUIDE_SUBJECT' => 'APPROVED TRAVEL GUIDE',
            'CONTACT_US_SUBJECT' => 'Thanks for contacting Buggl',
			'REVIEW_SUBJECT' => 'A traveler on Buggl rated you!',
            'SOLD_GUIDE' => 'You sold a Buggl Guide!',
            'PURCHASED_GUIDE' => 'Thanks for your purchase on Buggl',
            'DOWNLOAD_GUIDE' => 'Thanks for your download on Buggl (some will be free)',
            'SUSPENDED_ACCOUNT' => 'Your Buggl account is suspended',

            'web_path' => __DIR__.'/../../../../web/',

            'buggl_beta_authenticated' => 'buggl_beta_authenticated',

			'CONTACT_US_UNREAD' => 0,

			'PAYPAL_PAYMENT_CREATED' => 0,
			'PAYPAL_PAYMENT_COMPLETED' => 1,
			
			'MSG_NOTIF_PENDING' => 0,
			'MSG_NOTIF_SENT' => 1,
			'MSG_NOTIF_NO_NEED' => 2,
			'MSG_NOTIF_UNSENT' => 3,
			
			'site_url' => 'http://www.buggl.com',
			
			'FEATURED_IN_PROFILE_LIMIT' => 3,
			
			'buggl_payment_share' => 0.20,

			// amazon s3 folder keys
			'S3_BASE_URL' => 'http://localhost/buggl/web/',
			'EGUIDE_PDF' => 'uploads/eguide_pdf/',
			'EGUIDE_PHOTOS' => 'uploads/travel_guide_photos/',
			'SPOT_PHOTOS' => 'uploads/spot_pics/',
			'FEATURED_GUIDE_PHOTOS' => 'uploads/rotating_feature/',

			// google map api
			// local key
			// 'google_maps_api_key' => 'AIzaSyD-oevMEHkHjtuqVz5vhg6oAZsqLpueRIQ'
			// prod key 
			'google_maps_api_key' => 'AIzaSyBEoojfv0KL3vIx5RQjWYIKZcqxJBH4LXc'
        );
    }

    public function get($key=null)
    {
        if(is_null($key) || !array_key_exists($key, $this->constants)){
            return null;
        }

        return $this->constants[$key];
    }
}
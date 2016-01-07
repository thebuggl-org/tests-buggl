<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appProdUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appProdUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);

        if (0 === strpos($pathinfo, '/')) {
            // _assetic_024ca4a
            if ($pathinfo === '/js/024ca4a.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '024ca4a',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_024ca4a',);
            }

            // _assetic_024ca4a_0
            if ($pathinfo === '/js/024ca4a_jquery.search.eguide_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '024ca4a',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_024ca4a_0',);
            }

            // _assetic_faa3fa6
            if ($pathinfo === '/js/faa3fa6.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'faa3fa6',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_faa3fa6',);
            }

            // _assetic_faa3fa6_0
            if ($pathinfo === '/js/faa3fa6_chosen.jquery.min_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'faa3fa6',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_faa3fa6_0',);
            }

            // _assetic_467475a
            if ($pathinfo === '/css/467475a.css') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '467475a',  'pos' => NULL,  '_format' => 'css',  '_route' => '_assetic_467475a',);
            }

            // _assetic_467475a_0
            if ($pathinfo === '/css/467475a_chosen_1.css') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '467475a',  'pos' => '0',  '_format' => 'css',  '_route' => '_assetic_467475a_0',);
            }

            // _assetic_efffa90
            if ($pathinfo === '/js/efffa90.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'efffa90',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_efffa90',);
            }

            // _assetic_efffa90_0
            if ($pathinfo === '/js/efffa90_backbone.localauthor_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'efffa90',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_efffa90_0',);
            }

            // _assetic_e556f01
            if ($pathinfo === '/js/e556f01.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'e556f01',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_e556f01',);
            }

            // _assetic_e556f01_0
            if ($pathinfo === '/js/e556f01_jquery.pagination_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'e556f01',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_e556f01_0',);
            }

            // _assetic_ca82aa4
            if ($pathinfo === '/js/ca82aa4.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'ca82aa4',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_ca82aa4',);
            }

            // _assetic_ca82aa4_0
            if ($pathinfo === '/js/ca82aa4_backbone.billing_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'ca82aa4',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_ca82aa4_0',);
            }

            // _assetic_e31150e
            if ($pathinfo === '/js/e31150e.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'e31150e',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_e31150e',);
            }

            // _assetic_e31150e_0
            if ($pathinfo === '/js/e31150e_jquery.tinymce_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'e31150e',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_e31150e_0',);
            }

            // _assetic_8fecdc4
            if ($pathinfo === '/js/8fecdc4.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '8fecdc4',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_8fecdc4',);
            }

            // _assetic_8fecdc4_0
            if ($pathinfo === '/js/8fecdc4_backbone.ranking_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '8fecdc4',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_8fecdc4_0',);
            }

            // _assetic_cc4ebb9
            if ($pathinfo === '/js/cc4ebb9.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'cc4ebb9',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_cc4ebb9',);
            }

            // _assetic_cc4ebb9_0
            if ($pathinfo === '/js/cc4ebb9_jquery.manage.country_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'cc4ebb9',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_cc4ebb9_0',);
            }

            // _assetic_cc4ebb9_1
            if ($pathinfo === '/js/cc4ebb9_chosen.jquery.min_2.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'cc4ebb9',  'pos' => '1',  '_format' => 'js',  '_route' => '_assetic_cc4ebb9_1',);
            }

            // _assetic_cc4ebb9_2
            if ($pathinfo === '/js/cc4ebb9_backbone.country_3.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'cc4ebb9',  'pos' => '2',  '_format' => 'js',  '_route' => '_assetic_cc4ebb9_2',);
            }

            // _assetic_eba3cfc
            if ($pathinfo === '/js/eba3cfc.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'eba3cfc',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_eba3cfc',);
            }

            // _assetic_eba3cfc_0
            if ($pathinfo === '/js/eba3cfc_backbone.contactus_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'eba3cfc',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_eba3cfc_0',);
            }

            // _assetic_afe8bff
            if ($pathinfo === '/js/afe8bff.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'afe8bff',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_afe8bff',);
            }

            // _assetic_afe8bff_0
            if ($pathinfo === '/js/afe8bff_backbone.message_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'afe8bff',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_afe8bff_0',);
            }

            // _assetic_c3d99cc
            if ($pathinfo === '/js/c3d99cc.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'c3d99cc',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_c3d99cc',);
            }

            // _assetic_c3d99cc_0
            if ($pathinfo === '/js/c3d99cc_backbone.spot_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'c3d99cc',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_c3d99cc_0',);
            }

            // _assetic_a21e9f9
            if ($pathinfo === '/js/a21e9f9.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'a21e9f9',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_a21e9f9',);
            }

            // _assetic_a21e9f9_0
            if ($pathinfo === '/js/a21e9f9_markerclusterer_compiled_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'a21e9f9',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_a21e9f9_0',);
            }

            // _assetic_a21e9f9_1
            if ($pathinfo === '/js/a21e9f9_spots-map_2.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'a21e9f9',  'pos' => '1',  '_format' => 'js',  '_route' => '_assetic_a21e9f9_1',);
            }

            // _assetic_5d1a092
            if ($pathinfo === '/js/5d1a092.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '5d1a092',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_5d1a092',);
            }

            // _assetic_5d1a092_0
            if ($pathinfo === '/js/5d1a092_jquery.budget-chooser_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '5d1a092',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_5d1a092_0',);
            }

            // _assetic_b841717
            if ($pathinfo === '/js/b841717.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'b841717',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_b841717',);
            }

            // _assetic_b841717_0
            if ($pathinfo === '/js/b841717_eguiderequest_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'b841717',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_b841717_0',);
            }

            // _assetic_7f0ec13
            if ($pathinfo === '/js/7f0ec13.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '7f0ec13',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_7f0ec13',);
            }

            // _assetic_7f0ec13_0
            if ($pathinfo === '/js/7f0ec13_jquery.iframe-post-form_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '7f0ec13',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_7f0ec13_0',);
            }

            // _assetic_7f0ec13_1
            if ($pathinfo === '/js/7f0ec13_jquery.modal-edit_2.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '7f0ec13',  'pos' => '1',  '_format' => 'js',  '_route' => '_assetic_7f0ec13_1',);
            }

            // _assetic_002e66f
            if ($pathinfo === '/js/002e66f.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '002e66f',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_002e66f',);
            }

            // _assetic_002e66f_0
            if ($pathinfo === '/js/002e66f_backbone.gallery.v4_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '002e66f',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_002e66f_0',);
            }

            // _assetic_df13035
            if ($pathinfo === '/js/df13035.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'df13035',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_df13035',);
            }

            // _assetic_df13035_0
            if ($pathinfo === '/js/df13035_jquery.references_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'df13035',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_df13035_0',);
            }

            // _assetic_df13035_1
            if ($pathinfo === '/js/df13035_jquery.pagination_2.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => 'df13035',  'pos' => '1',  '_format' => 'js',  '_route' => '_assetic_df13035_1',);
            }

            // _assetic_71953e3
            if ($pathinfo === '/js/71953e3.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '71953e3',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_71953e3',);
            }

            // _assetic_71953e3_0
            if ($pathinfo === '/js/71953e3_jquery.change-review-status_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '71953e3',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_71953e3_0',);
            }

            // _assetic_6824d0e
            if ($pathinfo === '/js/6824d0e.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '6824d0e',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_6824d0e',);
            }

            // _assetic_6824d0e_0
            if ($pathinfo === '/js/6824d0e_jquery.iframe-post-form_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '6824d0e',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_6824d0e_0',);
            }

            // _assetic_6824d0e_1
            if ($pathinfo === '/js/6824d0e_jquery.modal_2.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '6824d0e',  'pos' => '1',  '_format' => 'js',  '_route' => '_assetic_6824d0e_1',);
            }

            // _assetic_31f2507
            if ($pathinfo === '/js/31f2507.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '31f2507',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_31f2507',);
            }

            // _assetic_31f2507_0
            if ($pathinfo === '/js/31f2507_jquery.modal_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '31f2507',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_31f2507_0',);
            }

            // _assetic_7cbd4f3
            if ($pathinfo === '/js/7cbd4f3.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '7cbd4f3',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_7cbd4f3',);
            }

            // _assetic_7cbd4f3_0
            if ($pathinfo === '/js/7cbd4f3_category_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '7cbd4f3',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_7cbd4f3_0',);
            }

            // _assetic_5be6780
            if ($pathinfo === '/js/5be6780.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '5be6780',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_5be6780',);
            }

            // _assetic_5be6780_0
            if ($pathinfo === '/js/5be6780_backbone.gallery_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '5be6780',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_5be6780_0',);
            }

            // _assetic_0f2eb9d
            if ($pathinfo === '/js/0f2eb9d.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '0f2eb9d',  'pos' => NULL,  '_format' => 'js',  '_route' => '_assetic_0f2eb9d',);
            }

            // _assetic_0f2eb9d_0
            if ($pathinfo === '/js/0f2eb9d_azulphotouploader-2.0b_1.js') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '0f2eb9d',  'pos' => '0',  '_format' => 'js',  '_route' => '_assetic_0f2eb9d_0',);
            }

            // _assetic_0eeb485
            if ($pathinfo === '/css/0eeb485.css') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '0eeb485',  'pos' => NULL,  '_format' => 'css',  '_route' => '_assetic_0eeb485',);
            }

            // _assetic_0eeb485_0
            if ($pathinfo === '/css/0eeb485_photouploader_1.css') {
                return array (  '_controller' => 'assetic.controller:render',  'name' => '0eeb485',  'pos' => '0',  '_format' => 'css',  '_route' => '_assetic_0eeb485_0',);
            }

        }

        if (0 === strpos($pathinfo, '/')) {
            // buggl_photo_homepage
            if ($pathinfo === '/photo') {
                return array (  '_controller' => 'Buggl\\PhotoBundle\\Controller\\DefaultController::indexAction',  '_route' => 'buggl_photo_homepage',);
            }

            // buggl_photo_upload
            if ($pathinfo === '/upload') {
                return array (  '_controller' => 'Buggl\\PhotoBundle\\Controller\\DefaultController::uploadAction',  '_route' => 'buggl_photo_upload',);
            }

        }

        if (0 === strpos($pathinfo, '/')) {
            // homepage_mark_up
            if ($pathinfo === '/markup/homepage') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::homepageAction',  '_route' => 'homepage_mark_up',);
            }

            // profile_mark_up
            if ($pathinfo === '/markup/profile') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::localExpertProfileAction',  '_route' => 'profile_mark_up',);
            }

            // result_page_mark_up
            if ($pathinfo === '/markup/result-page') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::resultPageAction',  '_route' => 'result_page_mark_up',);
            }

            // full_page_mark_up
            if ($pathinfo === '/markup/full-page') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::fullpageAction',  '_route' => 'full_page_mark_up',);
            }

            // full_page_local_places_mark_up
            if ($pathinfo === '/markup/local-places') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::localPlacesAction',  '_route' => 'full_page_local_places_mark_up',);
            }

            // contact_us_mark_up
            if ($pathinfo === '/markup/contact-us') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::contactUsAction',  '_route' => 'contact_us_mark_up',);
            }

            // about_us_mark_up
            if ($pathinfo === '/markup/about-us') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::aboutUsAction',  '_route' => 'about_us_mark_up',);
            }

            // our_story_mark_up
            if ($pathinfo === '/markup/our-story') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::ourStoryAction',  '_route' => 'our_story_mark_up',);
            }

            // our_mission_mark_up
            if ($pathinfo === '/markup/our-mission') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::ourMissionAction',  '_route' => 'our_mission_mark_up',);
            }

            // press_release_mark_up
            if ($pathinfo === '/markup/press-release') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::pressReleaseAction',  '_route' => 'press_release_mark_up',);
            }

            // guide_overview_mark_up
            if ($pathinfo === '/markup/guide-overview') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::guideOverviewAction',  '_route' => 'guide_overview_mark_up',);
            }

            // guide_before_leaving_mark_up
            if ($pathinfo === '/markup/guide-before-leaving') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::guideBeforeLeavingAction',  '_route' => 'guide_before_leaving_mark_up',);
            }

            // guide_itinerary_mark_up
            if ($pathinfo === '/markup/guide-itinerary') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::guideItineraryAction',  '_route' => 'guide_itinerary_mark_up',);
            }

            // become_a_guid_mark_up
            if ($pathinfo === '/markup/become-a-guide') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::becomeAguideAction',  '_route' => 'become_a_guid_mark_up',);
            }

            // admin_index_for_mark_up
            if ($pathinfo === '/markup') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::indexAction',  '_route' => 'admin_index_for_mark_up',);
            }

            // admin_login_mark_up
            if ($pathinfo === '/markup/admin/login') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::adminLoginAction',  '_route' => 'admin_login_mark_up',);
            }

            // admin_mark_up
            if ($pathinfo === '/markup/admin') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Markup\\MarkupController::adminAction',  '_route' => 'admin_mark_up',);
            }

            // gplus
            if ($pathinfo === '/getGplusCount') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Common\\ShareController::getGplusCountAction',  '_route' => 'gplus',);
            }

            // buggl_get_pin_count
            if ($pathinfo === '/get-pin-count') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Common\\ShareController::getPinCountAction',  '_route' => 'buggl_get_pin_count',);
            }

            // suggest_location
            if ($pathinfo === '/suggest-location') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\SearchController::autoSuggestLocationAction',  '_route' => 'suggest_location',);
            }

            // suggest_activity
            if ($pathinfo === '/suggest-activity') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\SearchController::autoSuggestActivityAction',  '_route' => 'suggest_activity',);
            }

            // buggl_guide_browse_all
            if ($pathinfo === '/browse-all-guides') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Search\\MainController::browseAllAction',  '_route' => 'buggl_guide_browse_all',);
            }

            // buggl_guide_search
            if ($pathinfo === '/search') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Search\\MainController::searchResultAction',  '_route' => 'buggl_guide_search',);
            }

            // sort_eguide
            if ($pathinfo === '/sort/eguide') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\SearchController::sortAction',  '_route' => 'sort_eguide',);
            }

            // count_search_guide
            if ($pathinfo === '/guide-search/count') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\SearchController::countAction',  '_route' => 'count_search_guide',);
            }

            // get_empty_search_result_template
            if ($pathinfo === '/get-empty-search-result-template') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\SearchController::getEmptySearchResultTemplateAction',  '_route' => 'get_empty_search_result_template',);
            }

            // search_more
            if ($pathinfo === '/fetch-more-guides') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\SearchController::fetchMoreAction',  '_route' => 'search_more',);
            }

            // eguide_preview
            if (0 === strpos($pathinfo, '/guide-preview') && preg_match('#^/guide\\-preview/(?P<eguide_id>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\GuideController::eguidePreviewAction',)), array('_route' => 'eguide_preview'));
            }

            // eguide_create_html
            if (0 === strpos($pathinfo, '/guide-create-html-file') && preg_match('#^/guide\\-create\\-html\\-file/(?P<eguide_id>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\GuideController::createHtmlAction',)), array('_route' => 'eguide_create_html'));
            }

            // eguide_download
            if (0 === strpos($pathinfo, '/download-guide') && preg_match('#^/download\\-guide/(?P<slug>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\GuideController::downloadGuideAction',)), array('_route' => 'eguide_download'));
            }

            // buggl_homepage
            if (rtrim($pathinfo, '/') === '') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'buggl_homepage');
                }

                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\MainController::homepageAction',  '_route' => 'buggl_homepage',);
            }

            // buggl_eguide_not_published
            if ($pathinfo === '/guide-not-yet-published') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\GuideController::guideNotPublishedAction',  '_route' => 'buggl_eguide_not_published',);
            }

            // buggl_local_author_profile
            if (0 === strpos($pathinfo, '/guide-author') && preg_match('#^/guide\\-author/(?P<slug>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\LocalAuthorController::profileAction',)), array('_route' => 'buggl_local_author_profile'));
            }

            // buggl_guide_results
            if (0 === strpos($pathinfo, '/guides') && preg_match('#^/guides/(?P<slug>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\GuideController::resultsPageAction',)), array('_route' => 'buggl_guide_results'));
            }

            // buggl_buy_guide2
            if ($pathinfo === '/buy-guide2') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\MainController::buyGuideAction',  '_route' => 'buggl_buy_guide2',);
            }

            // buggl_buy_guide_return_url
            if (0 === strpos($pathinfo, '/process-payment-result') && preg_match('#^/process\\-payment\\-result/(?P<eguideId>[^/]+)/(?P<buyerId>[^/]+)/(?P<trackingId>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\MainController::processPaymentAction',)), array('_route' => 'buggl_buy_guide_return_url'));
            }

            // buggl_paypal_ipn_listener
            if (0 === strpos($pathinfo, '/paypal-ipn-listener2') && preg_match('#^/paypal\\-ipn\\-listener2/(?P<eguideId>[^/]+)/(?P<buyerId>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\MainController::paypalIPNListenerAction',)), array('_route' => 'buggl_paypal_ipn_listener'));
            }

            // eguide_publish
            if (0 === strpos($pathinfo, '/guide') && preg_match('#^/guide/(?P<id>[^/]+)/(?P<status>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\GuideController::guidePublishdByUserAction',)), array('_route' => 'eguide_publish'));
            }

            // buggl_static_jobs
            if ($pathinfo === '/jobs') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\StaticController::jobsAction',  '_route' => 'buggl_static_jobs',);
            }

            // buggl_static_press
            if ($pathinfo === '/press') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\StaticController::pressAction',  '_route' => 'buggl_static_press',);
            }

            // buggl_static_faq
            if ($pathinfo === '/faq') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\StaticController::faqAction',  '_route' => 'buggl_static_faq',);
            }

            // buggl_static_terms
            if ($pathinfo === '/terms-of-use') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\StaticController::termsOfUseAction',  '_route' => 'buggl_static_terms',);
            }

            // buggl_static_contact_us
            if ($pathinfo === '/contact-us') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Common\\ContactUsController::contactUsAction',  '_route' => 'buggl_static_contact_us',);
            }

            // buggl_static_our_tribe
            if ($pathinfo === '/our-tribe') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\StaticController::ourTribeAction',  '_route' => 'buggl_static_our_tribe',);
            }

            // buggl_static_our_mission
            if ($pathinfo === '/our-mission') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\StaticController::ourMissionAction',  '_route' => 'buggl_static_our_mission',);
            }

            // buggl_static_privacy_policy
            if ($pathinfo === '/privacy-policy') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\StaticController::privacyPolicyAction',  '_route' => 'buggl_static_privacy_policy',);
            }

            // buggl_write_a_guide
            if ($pathinfo === '/become-an-expert') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\StaticController::writeAGuideAction',  '_route' => 'buggl_write_a_guide',);
            }

            // buggl_how_it_works
            if ($pathinfo === '/how-it-works') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\StaticController::howItWorksAction',  '_route' => 'buggl_how_it_works',);
            }

            // buggl_post_guide_review
            if ($pathinfo === '/post-guide-review') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\ReviewsController::saveTravelGuideReviewAction',  '_route' => 'buggl_post_guide_review',);
            }

            // buggl_post_local_author_review
            if ($pathinfo === '/post-local-author-review') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\ReviewsController::saveLocalAuthorReviewAction',  '_route' => 'buggl_post_local_author_review',);
            }

            // buggl_remove_from_wishlist
            if ($pathinfo === '/wishlist/remove-this') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\WishlistController::removeFromWishlistAction',  '_route' => 'buggl_remove_from_wishlist',);
            }

            // buggl_remove_from_wishlist_guide
            if ($pathinfo === '/wishlist/remove-this/guide') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\WishlistController::removeFromWishlistGuideAction',  '_route' => 'buggl_remove_from_wishlist_guide',);
            }

            // buggl_add_to_wishlist
            if ($pathinfo === '/wishlist/add-this') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\WishlistController::addToWishlistAction',  '_route' => 'buggl_add_to_wishlist',);
            }

            // buggl_static_contest3
            if ($pathinfo === '/eat-and-drink') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\StaticController::contestAction',  '_route' => 'buggl_static_contest3',);
            }

            // buggl_static_contest4
            if ($pathinfo === '/50aguide') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\StaticController::contestAction',  '_route' => 'buggl_static_contest4',);
            }

            if (0 === strpos($pathinfo, '/local-author')) {
                // account_upgrade
                if ($pathinfo === '/local-author/upgrade-account') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\AccountUpgradeController::upgradeAction',  '_route' => 'account_upgrade',);
                }

                // local_author_login_details
                if ($pathinfo === '/local-author/login-details') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\LoginDetailsController::indexAction',  '_route' => 'local_author_login_details',);
                }

                // local_author_dashboard
                if ($pathinfo === '/local-author/dashboard') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\DashboardController::indexAction',  '_route' => 'local_author_dashboard',);
                }

                // local_author_beta_invite_list
                if (0 === strpos($pathinfo, '/local-author/beta-invites') && preg_match('#^/local\\-author/beta\\-invites(?:/(?P<type>[^/]+))?$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\BetaInviteController::indexAction',  'type' => 'pending',)), array('_route' => 'local_author_beta_invite_list'));
                }

                // local_author_beta_invite
                if ($pathinfo === '/local-author/invite-for-beta') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\BetaInviteController::inviteAction',  '_route' => 'local_author_beta_invite',);
                }

                // local_author_mass_invite
                if ($pathinfo === '/local-author/invite-authors') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\BetaInviteController::massInviteAction',  '_route' => 'local_author_mass_invite',);
                }

                // local_author_share
                if ($pathinfo === '/local-author/share-buggl') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EarnMoreController::shareBugglAction',  '_route' => 'local_author_share',);
                }

                // local_author_beta_invite_pagination
                if (0 === strpos($pathinfo, '/local-author/beta-invites-list') && preg_match('#^/local\\-author/beta\\-invites\\-list/(?P<type>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\BetaInviteController::inviteListAction',)), array('_route' => 'local_author_beta_invite_pagination'));
                }

                // local_author_beta_invite_resend
                if (0 === strpos($pathinfo, '/local-author/beta-invite/resend') && preg_match('#^/local\\-author/beta\\-invite/resend/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\BetaInviteController::resendAction',)), array('_route' => 'local_author_beta_invite_resend'));
                }

                // local_author_beta_invite_delete
                if (0 === strpos($pathinfo, '/local-author/beta-invite/delete') && preg_match('#^/local\\-author/beta\\-invite/delete/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\BetaInviteController::deleteAction',)), array('_route' => 'local_author_beta_invite_delete'));
                }

                // local_author_featue_guide
                if (0 === strpos($pathinfo, '/local-author/e-guides/feature') && preg_match('#^/local\\-author/e\\-guides/feature/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::featureInProfileAction',)), array('_route' => 'local_author_featue_guide'));
                }

                // local_author_unfeature_guide
                if (0 === strpos($pathinfo, '/local-author/e-guides/unfeature') && preg_match('#^/local\\-author/e\\-guides/unfeature/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::unFeatureInProfileAction',)), array('_route' => 'local_author_unfeature_guide'));
                }

                // buggl_display_wishlist
                if ($pathinfo === '/local-author/wishlist') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\WishlistController::displayWishlistAction',  '_route' => 'buggl_display_wishlist',);
                }

                // buggl_remove_wish
                if (0 === strpos($pathinfo, '/local-author/remove-wish') && preg_match('#^/local\\-author/remove\\-wish/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\WishlistController::removeWishAction',)), array('_route' => 'buggl_remove_wish'));
                }

                // local_author_reviews
                if ($pathinfo === '/local-author/reviews') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReviewController::indexAction',  '_route' => 'local_author_reviews',);
                }

                // local_author_new_travel_guides_reviews
                if ($pathinfo === '/local-author/new-travel-guide-reviews') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReviewController::newTravelGuideReviewsAction',  '_route' => 'local_author_new_travel_guides_reviews',);
                }

                // local_author_new_reviews
                if ($pathinfo === '/local-author/new-reviews') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReviewController::newLocalAuthorReviewsAction',  '_route' => 'local_author_new_reviews',);
                }

                // local_author_denied_reviews
                if ($pathinfo === '/local-author/denied-reviews') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReviewController::deniedReviewsAction',  '_route' => 'local_author_denied_reviews',);
                }

                // local_author_change_review_status
                if ($pathinfo === '/local-author/change-status-review') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReviewController::changeStatusAction',  '_route' => 'local_author_change_review_status',);
                }

                // local_author_follow
                if ($pathinfo === '/local-author/follow') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\FollowController::followAction',  '_route' => 'local_author_follow',);
                }

                // local_author_followed
                if ($pathinfo === '/local-author/followed') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\FollowController::followedAction',  '_route' => 'local_author_followed',);
                }

                // local_author_follower
                if ($pathinfo === '/local-author/follower') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\FollowController::followerAction',  '_route' => 'local_author_follower',);
                }

                // pdf_preview
                if (0 === strpos($pathinfo, '/local-author/pdf') && preg_match('#^/local\\-author/pdf/(?P<filename>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::pdfPreviewAction',)), array('_route' => 'pdf_preview'));
                }

                // add_travel_guide_info
                if ($pathinfo === '/local-author/add-travel-guide-info') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::addTravelGuideAction',  '_route' => 'add_travel_guide_info',);
                }

                // add_e_guide_info
                if (0 === strpos($pathinfo, '/local-author/add-travel-guide-info') && preg_match('#^/local\\-author/add\\-travel\\-guide\\-info/(?P<slag>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::addRequestGuideAction',)), array('_route' => 'add_e_guide_info'));
                }

                // update_travel_guide_info
                if (0 === strpos($pathinfo, '/local-author/travel-guide-info') && preg_match('#^/local\\-author/travel\\-guide\\-info/(?P<travel_guide_id>\\d+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::addTravelGuideAction',)), array('_route' => 'update_travel_guide_info'));
                }

                // travel_guide_cover_page
                if (0 === strpos($pathinfo, '/local-author/travel-guide-pages') && preg_match('#^/local\\-author/travel\\-guide\\-pages(?:/(?P<travel_guide_id>\\d+)(?:/(?P<page_name>[^/]+)(?:/(?P<page>\\d+)(?:/(?P<day>\\d+)(?:/(?P<time_of_day>[^/]+)(?:/(?P<time_of_day_cnt>[^/]+))?)?)?)?)?)?$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::updateGuidePagesAction',  'travel_guide_id' => '0',  'page_name' => 'cover',  'day' => '1',  'page' => '1',  'time_of_day' => '0',  'time_of_day_cnt' => '0',)), array('_route' => 'travel_guide_cover_page'));
                }

                // finishing_travel_guide
                if (0 === strpos($pathinfo, '/local-author/finish-your-travel-guide') && preg_match('#^/local\\-author/finish\\-your\\-travel\\-guide/(?P<travel_guide_id>\\d+)(?:/(?P<page>\\d+))?$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::finishTravelGuideAction',  'page' => '1',)), array('_route' => 'finishing_travel_guide'));
                }

                // finishing_travel_guide_spot_list
                if (0 === strpos($pathinfo, '/local-author/finish-your-travel-guide-spot-list') && preg_match('#^/local\\-author/finish\\-your\\-travel\\-guide\\-spot\\-list/(?P<travel_guide_id>\\d+)(?:/(?P<page>\\d+))?$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::finishTravelGuideSpotListAction',  'page' => '1',)), array('_route' => 'finishing_travel_guide_spot_list'));
                }

                // feature_eguide_spot
                if (0 === strpos($pathinfo, '/local-author/feature-travel-guide-spot') && preg_match('#^/local\\-author/feature\\-travel\\-guide\\-spot/(?P<travel_guide_id>\\d+)/(?P<spot_id>\\d+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::featureEguideSpotAction',)), array('_route' => 'feature_eguide_spot'));
                }

                // update_eguide_field
                if (0 === strpos($pathinfo, '/local-author/update-travel-guide') && preg_match('#^/local\\-author/update\\-travel\\-guide/(?P<guide_slug>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::updateGuideFieldAction',)), array('_route' => 'update_eguide_field'));
                }

                // update_eguide_overview
                if (0 === strpos($pathinfo, '/local-author/update-travel-guide-content') && preg_match('#^/local\\-author/update\\-travel\\-guide\\-content/(?P<guide_slug>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::updateGuideContentAction',)), array('_route' => 'update_eguide_overview'));
                }

                // create_itinerary
                if (0 === strpos($pathinfo, '/local-author/create-itinerary') && preg_match('#^/local\\-author/create\\-itinerary/(?P<guide_slug>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::createItineraryAction',)), array('_route' => 'create_itinerary'));
                }

                // update_itinerary_intro
                if (0 === strpos($pathinfo, '/local-author/update-itinerary-intro') && preg_match('#^/local\\-author/update\\-itinerary\\-intro/(?P<guide_slug>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::updateItineraryIntroAction',)), array('_route' => 'update_itinerary_intro'));
                }

                // update_before_you_go
                if (0 === strpos($pathinfo, '/local-author/update-before-you-go') && preg_match('#^/local\\-author/update\\-before\\-you\\-go/(?P<guide_slug>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::updateBeforeYouGoPageAction',)), array('_route' => 'update_before_you_go'));
                }

                // process_temp_eguide_photo
                if ($pathinfo === '/local-author/process-temp-travel-guide-photo') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::uploadPhotoAction',  '_route' => 'process_temp_eguide_photo',);
                }

                // process_temp_eguide_photo_from_web
                if ($pathinfo === '/local-author/process-temp-travel-guide-photo-from-web') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::getPhotoFromWebAction',  '_route' => 'process_temp_eguide_photo_from_web',);
                }

                // crop_eguide_photo
                if ($pathinfo === '/local-author/crop-travel-guide-photo') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::cropPhotoAction',  '_route' => 'crop_eguide_photo',);
                }

                // crop_spot_detail_photo
                if ($pathinfo === '/local-author/crop-spot-desc-photo') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::cropPhotoAction',  '_route' => 'crop_spot_detail_photo',);
                }

                // find_spot_detail
                if ($pathinfo === '/local-author/spot-detail') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::findSpotAction',  '_route' => 'find_spot_detail',);
                }

                // fetch_spot_info
                if ($pathinfo === '/local-author/fetch-spot-info') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::fetchSpotInfoAction',  '_route' => 'fetch_spot_info',);
                }

                // add_spot_form
                if ($pathinfo === '/local-author/get-add-spot-form') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::getAddSpotFormAction',  '_route' => 'add_spot_form',);
                }

                // edit_spot_form
                if ($pathinfo === '/local-author/get-edit-spot-form') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::getEditSpotFormAction',  '_route' => 'edit_spot_form',);
                }

                // check_spot_duplicate
                if ($pathinfo === '/local-author/check-spot-availability') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::checkSpotAvailabilityAction',  '_route' => 'check_spot_duplicate',);
                }

                // get_spot_list
                if ($pathinfo === '/local-author/get-spots-list') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::getSpotsListAction',  '_route' => 'get_spot_list',);
                }

                // get_spot_details
                if (0 === strpos($pathinfo, '/local-author/fetch-spot-details') && preg_match('#^/local\\-author/fetch\\-spot\\-details/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::fetchSpotDetailsAction',)), array('_route' => 'get_spot_details'));
                }

                // view_spot_details
                if (0 === strpos($pathinfo, '/local-author/view-spot-details') && preg_match('#^/local\\-author/view\\-spot\\-details/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::viewSpotDetailsAction',)), array('_route' => 'view_spot_details'));
                }

                // save_spot
                if ($pathinfo === '/local-author/save-spot') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::saveAction',  '_route' => 'save_spot',);
                }

                // update_spot
                if ($pathinfo === '/local-author/update-spot') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::updateAction',  '_route' => 'update_spot',);
                }

                // save_custom_spot_category
                if ($pathinfo === '/local-author/save-custom-spot-category') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::saveCustomCategoryAction',  '_route' => 'save_custom_spot_category',);
                }

                // save_custom_spot_like
                if ($pathinfo === '/local-author/save-custom-spot-like') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::saveCustomSpotLikeAction',  '_route' => 'save_custom_spot_like',);
                }

                // add_spot_to_eguide
                if (0 === strpos($pathinfo, '/local-author/add-spot-to-guide') && preg_match('#^/local\\-author/add\\-spot\\-to\\-guide/(?P<guide_id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::addSpotToGuideAction',)), array('_route' => 'add_spot_to_eguide'));
                }

                // get_spot_info
                if (0 === strpos($pathinfo, '/local-author/get-spot-details') && preg_match('#^/local\\-author/get\\-spot\\-details/(?P<spot_id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::getSpotDetailsAction',)), array('_route' => 'get_spot_info'));
                }

                // upload_spot_photo
                if ($pathinfo === '/local-author/upload-spot-photo') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::uploadPhotoAction',  '_route' => 'upload_spot_photo',);
                }

                // update_spot_description
                if ($pathinfo === '/local-author/update-spot-description') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::updateSpotDescriptionAction',  '_route' => 'update_spot_description',);
                }

                // travel_guide_itinerary
                if (0 === strpos($pathinfo, '/local-author/travel-guide-itinerary') && preg_match('#^/local\\-author/travel\\-guide\\-itinerary/(?P<travel_guide_id>\\d+)(?:/(?P<page>\\d+))?$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::itineraryAction',  'page' => '1',)), array('_route' => 'travel_guide_itinerary'));
                }

                // flickr_photo_search
                if ($pathinfo === '/local-author/flickr-photo-search') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::flickrSearchAction',  '_route' => 'flickr_photo_search',);
                }

                // add_new_day
                if (0 === strpos($pathinfo, '/local-author/add-new-day') && preg_match('#^/local\\-author/add\\-new\\-day/(?P<guide_slug>[^/]+)/(?P<day_num>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::addNewDayAction',)), array('_route' => 'add_new_day'));
                }

                // remove_day
                if (0 === strpos($pathinfo, '/local-author/remove-day') && preg_match('#^/local\\-author/remove\\-day/(?P<guide_slug>[^/]+)/(?P<day_num>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::removeDayAction',)), array('_route' => 'remove_day'));
                }

                // remove_spot
                if (0 === strpos($pathinfo, '/local-author/remove-spot') && preg_match('#^/local\\-author/remove\\-spot/(?P<id>[^/]+)/(?P<type>[^/]+)/(?P<guide_id>[^/]+)/(?P<day_num>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::removeSpotAction',)), array('_route' => 'remove_spot'));
                }

                // eguide_delete
                if (0 === strpos($pathinfo, '/local-author/delete-guide') && preg_match('#^/local\\-author/delete\\-guide/(?P<slug>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::deleteGuideAction',)), array('_route' => 'eguide_delete'));
                }

                // eguide_archive
                if (0 === strpos($pathinfo, '/local-author/archive-guide') && preg_match('#^/local\\-author/archive\\-guide/(?P<slug>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::archiveGuideAction',)), array('_route' => 'eguide_archive'));
                }

                // eguide_unarchive
                if (0 === strpos($pathinfo, '/local-author/unarchive-guide') && preg_match('#^/local\\-author/unarchive\\-guide/(?P<slug>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::unarchiveGuideAction',)), array('_route' => 'eguide_unarchive'));
                }

                // republish_guide
                if (0 === strpos($pathinfo, '/local-author/submit-for-approval') && preg_match('#^/local\\-author/submit\\-for\\-approval/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::republishAction',)), array('_route' => 'republish_guide'));
                }

                // create_guide_pdf
                if (0 === strpos($pathinfo, '/local-author/create-pdf') && preg_match('#^/local\\-author/create\\-pdf/(?P<eguide_id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::createPDFAction',)), array('_route' => 'create_guide_pdf'));
                }

                // local_author_gallery
                if ($pathinfo === '/local-author/gallery-and-spots') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\MediaGalleryController::indexAction',  '_route' => 'local_author_gallery',);
                }

                // local_author_fetch_more_images
                if ($pathinfo === '/local-author/fetch-more') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\MediaGalleryController::fetchPictureAction',  '_route' => 'local_author_fetch_more_images',);
                }

                // local_author_gallery_videos
                if ($pathinfo === '/local-author/gallery-and-spots/videos') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\MediaGalleryController::videosAction',  '_route' => 'local_author_gallery_videos',);
                }

                // local_author_gallery_spots
                if (0 === strpos($pathinfo, '/local-author/gallery-and-spots/spots') && preg_match('#^/local\\-author/gallery\\-and\\-spots/spots(?:/(?P<type>[^/]+))?$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::indexAction',  'type' => 'all',)), array('_route' => 'local_author_gallery_spots'));
                }

                // local_author_gallery_spot_descriptions
                if (0 === strpos($pathinfo, '/local-author/gallery-and-spots/spot-descriptions') && preg_match('#^/local\\-author/gallery\\-and\\-spots/spot\\-descriptions/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SpotController::viewSpotDescriptionsAction',)), array('_route' => 'local_author_gallery_spot_descriptions'));
                }

                // local_author_upload_pic
                if ($pathinfo === '/local-author/upload-pic') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\MediaGalleryController::uploadPicturesAction',  '_route' => 'local_author_upload_pic',);
                }

                // local_author_delete_pic
                if ($pathinfo === '/local-author/delete-pic') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\MediaGalleryController::deletePicturesAction',  '_route' => 'local_author_delete_pic',);
                }

                // local_author_messages_archive
                if (0 === strpos($pathinfo, '/local-author/messages/archive') && preg_match('#^/local\\-author/messages/archive/(?P<messageThreadToUserId>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\MessagesController::archiveAction',)), array('_route' => 'local_author_messages_archive'));
                }

                // local_author_messages_unarchive
                if (0 === strpos($pathinfo, '/local-author/messages/unarchive') && preg_match('#^/local\\-author/messages/unarchive/(?P<messageThreadToUserId>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\MessagesController::unarchiveAction',)), array('_route' => 'local_author_messages_unarchive'));
                }

                // local_author_messages_create
                if (0 === strpos($pathinfo, '/local-author/messages/create') && preg_match('#^/local\\-author/messages/create(?:/(?P<slug>[^/]+))?$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\MessagesController::createAction',  'slug' => '',)), array('_route' => 'local_author_messages_create'));
                }

                // local_author_messages_reply
                if ($pathinfo === '/local-author/messages/reply') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\MessagesController::replyToMessageAction',  '_route' => 'local_author_messages_reply',);
                }

                // local_author_messages_pagination
                if (0 === strpos($pathinfo, '/local-author/messages-pagination') && preg_match('#^/local\\-author/messages\\-pagination(?:/(?P<type>[^/]+))?$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\MessagesController::messageThreadListAction',  'type' => 'inbox',)), array('_route' => 'local_author_messages_pagination'));
                }

                // local_author_messages_thread
                if (0 === strpos($pathinfo, '/local-author/messages') && preg_match('#^/local\\-author/messages/(?P<type>[^/]+)/(?P<messageThreadToUserId>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\MessagesController::messageThreadAction',)), array('_route' => 'local_author_messages_thread'));
                }

                // local_author_check_new_message
                if (0 === strpos($pathinfo, '/local-author/messages-check-new') && preg_match('#^/local\\-author/messages\\-check\\-new/(?P<threadId>[^/]+)/(?P<userId>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\MessagesController::checkNewMessageAction',)), array('_route' => 'local_author_check_new_message'));
                }

                // local_author_check_new_thread_message
                if (0 === strpos($pathinfo, '/local-author/messages-check-new-thread') && preg_match('#^/local\\-author/messages\\-check\\-new\\-thread/(?P<userId>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\MessagesController::checkNewThreadMessagesAction',)), array('_route' => 'local_author_check_new_thread_message'));
                }

                // local_author_messages
                if (0 === strpos($pathinfo, '/local-author/messages') && preg_match('#^/local\\-author/messages/(?P<type>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\MessagesController::indexAction',)), array('_route' => 'local_author_messages'));
                }

                // local_author_eguide
                if (0 === strpos($pathinfo, '/local-author/guide-request') && preg_match('#^/local\\-author/guide\\-request/(?P<type>[^/]+)/?$#s', $pathinfo, $matches)) {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'local_author_eguide');
                    }

                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EGuideRequestMessageController::indexAction',)), array('_route' => 'local_author_eguide'));
                }

                // local_author_eguide_request
                if (0 === strpos($pathinfo, '/local-author/req') && preg_match('#^/local\\-author/req/(?P<req>[^/]+)/(?P<status>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\DashboardController::eguideRequestStatusAction',)), array('_route' => 'local_author_eguide_request'));
                }

                // local_author_payments_purchased
                if ($pathinfo === '/local-author/e-guides/purchased') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\PaymentsController::purchasedGuidesAction',  '_route' => 'local_author_payments_purchased',);
                }

                // local_author_payments_sold
                if ($pathinfo === '/local-author/e-guides/sold') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\PaymentsController::soldGuidesAction',  '_route' => 'local_author_payments_sold',);
                }

                // local_author_paypal_settings
                if ($pathinfo === '/local-author/payments/settings') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\PaymentsController::paypalSettingsAction',  '_route' => 'local_author_paypal_settings',);
                }

                // local_author_disconnect_paypal_settings
                if (0 === strpos($pathinfo, '/local-author/payments/settings/disconect') && preg_match('#^/local\\-author/payments/settings/disconect/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\PaymentsController::disconnectPaypalAccountAction',)), array('_route' => 'local_author_disconnect_paypal_settings'));
                }

                // local_author_payments_settings
                if ($pathinfo === '/local-author/payments/settings') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\PaymentsController::settingsAction',  '_route' => 'local_author_payments_settings',);
                }

                // local_author_payments
                if ($pathinfo === '/local-author/payments/transaction-history') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\PaymentsController::indexAction',  '_route' => 'local_author_payments',);
                }

                // local_author_purchase_history_pagination
                if ($pathinfo === '/local-author/payments/purchase-history/pagination') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\PaymentsController::purchaseHistoryListAction',  '_route' => 'local_author_purchase_history_pagination',);
                }

                // local_author_purchased_guides_pagination
                if ($pathinfo === '/local-author/payments/purchased-guides/pagination') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\PaymentsController::purchasedGuidesListAction',  '_route' => 'local_author_purchased_guides_pagination',);
                }

                // local_author_sold_guides_pagination
                if ($pathinfo === '/local-author/payments/sold-guides/pagination') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\PaymentsController::soldGuidesListAction',  '_route' => 'local_author_sold_guides_pagination',);
                }

                // local_author_social_media
                if ($pathinfo === '/local-author/social-connect') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::indexAction',  '_route' => 'local_author_social_media',);
                }

                // refresh_facebook_token
                if (0 === strpos($pathinfo, '/local-author/social-media/refresh-facebook-token') && preg_match('#^/local\\-author/social\\-media/refresh\\-facebook\\-token/(?P<route>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::refreshFacebookAccessTokenAction',)), array('_route' => 'refresh_facebook_token'));
                }

                // refresh_google_plus_token
                if (0 === strpos($pathinfo, '/local-author/social-media/refresh-google-plus-token') && preg_match('#^/local\\-author/social\\-media/refresh\\-google\\-plus\\-token/(?P<route>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::refreshGooglePlusAccessTokenAction',)), array('_route' => 'refresh_google_plus_token'));
                }

                // connect_with_facebook_url
                if ($pathinfo === '/local-author/social-media/connect-with-facebook') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::connectWithFacebookUrlAction',  '_route' => 'connect_with_facebook_url',);
                }

                // connect_with_facebook_redirect_url
                if ($pathinfo === '/local-author/social-media/connect-with-facebook-redirect-url') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::connectWithFacebookRedirectAction',  '_route' => 'connect_with_facebook_redirect_url',);
                }

                // connect_with_facebook
                if ($pathinfo === '/local-author/social-media/connect-with-facebook-final-step') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::connectWithFacebookAction',  '_route' => 'connect_with_facebook',);
                }

                // connect_with_twitter_url
                if ($pathinfo === '/local-author/social-media/connect-with-twitter') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::connectWithTwitterUrlAction',  '_route' => 'connect_with_twitter_url',);
                }

                // connect_with_twitter_redirect_url
                if ($pathinfo === '/local-author/social-media/connect-with-twitter-redirect-url') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::connectWithTwitterRedirectAction',  '_route' => 'connect_with_twitter_redirect_url',);
                }

                // connect_with_twitter
                if ($pathinfo === '/local-author/social-media/connect-with-twitter-final-step') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::connectWithTwitterAction',  '_route' => 'connect_with_twitter',);
                }

                // connect_with_google_plus_url
                if ($pathinfo === '/local-author/social-media/connect-with-google') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::connectWithGooglePlusUrlAction',  '_route' => 'connect_with_google_plus_url',);
                }

                // connect_with_google_plus_redirect_url
                if ($pathinfo === '/local-author/social-media/connect-with-google-redirect-url') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::connectWithGooglePlusRedirectAction',  '_route' => 'connect_with_google_plus_redirect_url',);
                }

                // connect_with_google_plus
                if ($pathinfo === '/local-author/social-media/connect-with-google-final-step') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::connectWithGooglePlusAction',  '_route' => 'connect_with_google_plus',);
                }

                // disconnect_facebook
                if ($pathinfo === '/local-author/social-media/disconnect-facebook') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::disconnectFacebookAction',  '_route' => 'disconnect_facebook',);
                }

                // disconnect_twitter
                if ($pathinfo === '/local-author/social-media/disconnect-twitter') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::disconnectTwitterAction',  '_route' => 'disconnect_twitter',);
                }

                // disconnect_google_plus
                if ($pathinfo === '/local-author/social-media/disconnect-google') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::disconnectGooglePlusAction',  '_route' => 'disconnect_google_plus',);
                }

                // local_author_resend_email_update_email
                if ($pathinfo === '/local-author/login-details/resend-email-address-update-confirmation-email') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\LoginDetailsController::resendEmailUpdateAction',  '_route' => 'local_author_resend_email_update_email',);
                }

                // local_author_cancel_email_update_email
                if ($pathinfo === '/local-author/login-details/cancel-email-address-update-confirmation-email') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\LoginDetailsController::cancelEmailUpdateAction',  '_route' => 'local_author_cancel_email_update_email',);
                }

                // local_author_confirm_email_update
                if (0 === strpos($pathinfo, '/local-author/login-details/confirm-email-address-update') && preg_match('#^/local\\-author/login\\-details/confirm\\-email\\-address\\-update/(?P<token>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\LoginDetailsController::confirmEmailUpdateAction',)), array('_route' => 'local_author_confirm_email_update'));
                }

                // local_author_update_email_failed
                if ($pathinfo === '/local-author/login-details/update-email-address-failed') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\LoginDetailsController::confirmEmailUpdateFailedAction',  '_route' => 'local_author_update_email_failed',);
                }

                // upload_profile_pic
                if ($pathinfo === '/local-author/profile/upload-pic') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ProfileController::uploadProfilePicAction',  '_route' => 'upload_profile_pic',);
                }

                // crop_profile_pic
                if ($pathinfo === '/local-author/profile/crop-pic') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ProfileController::cropProfilePicAction',  '_route' => 'crop_profile_pic',);
                }

                // update_profile
                if (0 === strpos($pathinfo, '/local-author/profile/update-profile') && preg_match('#^/local\\-author/profile/update\\-profile/(?P<updateFor>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ProfileController::updateProfileAction',  '_format' => 'json',)), array('_route' => 'update_profile'));
                }

                // get_profile_form
                if (0 === strpos($pathinfo, '/local-author/profile/get-profile-form') && preg_match('#^/local\\-author/profile/get\\-profile\\-form/(?P<buildFor>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ProfileController::getProfileFormAction',)), array('_route' => 'get_profile_form'));
                }

                // update_local_author
                if (0 === strpos($pathinfo, '/local-author/profile/update-local-author') && preg_match('#^/local\\-author/profile/update\\-local\\-author/(?P<updateFor>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ProfileController::updateLocalAuthorAction',  '_format' => 'json',)), array('_route' => 'update_local_author'));
                }

                // get_local_author_form
                if (0 === strpos($pathinfo, '/local-author/profile/get-local-author-form') && preg_match('#^/local\\-author/profile/get\\-local\\-author\\-form/(?P<buildFor>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ProfileController::getLocalAuthorFormAction',)), array('_route' => 'get_local_author_form'));
                }

                // get_local_interest_form
                if (0 === strpos($pathinfo, '/local-author/profile/get-local-interest-form') && preg_match('#^/local\\-author/profile/get\\-local\\-interest\\-form(?:/(?P<localInterestId>[^/]+))?$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ProfileController::getLocalInterestFormAction',  'localInterestId' => '0',)), array('_route' => 'get_local_interest_form'));
                }

                // add_local_interest
                if ($pathinfo === '/local-author/profile/add-local-interest') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ProfileController::addLocalInterestAction',  '_route' => 'add_local_interest',);
                }

                // edit_local_interest
                if (0 === strpos($pathinfo, '/local-author/profile/edit-local-interest') && preg_match('#^/local\\-author/profile/edit\\-local\\-interest/(?P<localInterestId>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ProfileController::editLocalInterestAction',)), array('_route' => 'edit_local_interest'));
                }

                // delete_local_interest
                if (0 === strpos($pathinfo, '/local-author/profile/delete-local-interest') && preg_match('#^/local\\-author/profile/delete\\-local\\-interest/(?P<localInterestId>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ProfileController::deleteLocalInterestAction',  '_format' => 'json',)), array('_route' => 'delete_local_interest'));
                }

                // upload_local_interest_photo
                if ($pathinfo === '/local-author/profile/upload-local-interest-photo') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ProfileController::uploadLocalInterestPhotoAction',  '_format' => 'json',  '_route' => 'upload_local_interest_photo',);
                }

                // get_travel_info_form
                if (0 === strpos($pathinfo, '/local-author/profile/get-travel-info-form') && preg_match('#^/local\\-author/profile/get\\-travel\\-info\\-form/(?P<fieldId>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ProfileController::getTravelInfoFormAction',)), array('_route' => 'get_travel_info_form'));
                }

                // update_travel_info
                if (0 === strpos($pathinfo, '/local-author/profile/update-travel-info-form') && preg_match('#^/local\\-author/profile/update\\-travel\\-info\\-form/(?P<fieldId>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ProfileController::updateTravelInfoAction',  '_format' => 'json',)), array('_route' => 'update_travel_info'));
                }

                // get_eguide_step2_cover
                if ($pathinfo === '/local-author/get-step2-inner-page') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::step2TemplateAction',  '_format' => 'json',  '_route' => 'get_eguide_step2_cover',);
                }

                // get_new_access_token
                if ($pathinfo === '/local-author/profile/get-access-token') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\DashboardController::getNewAccessTokenAction',  '_route' => 'get_new_access_token',);
                }

                // get_analytics_data
                if ($pathinfo === '/local-author/dashboard/get-analytics-data') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\DashboardController::getAnalyticsDataAction',  '_format' => 'json',  '_route' => 'get_analytics_data',);
                }

                // get_activity_feed
                if ($pathinfo === '/local-author/dashboard/get-activity-feed') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\DashboardController::activitiesAction',  '_route' => 'get_activity_feed',);
                }

                // get_oauth2_access_token
                if ($pathinfo === '/local-author/references/gmail/get-acccess-token') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReferencesController::getAccessTokenAction',  '_route' => 'get_oauth2_access_token',);
                }

                // get_gmail_contacts
                if ($pathinfo === '/local-author/references/get-gmail-contacts') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReferencesController::getGmailContactsAction',  '_route' => 'get_gmail_contacts',);
                }

                // request_gmail_references
                if ($pathinfo === '/local-author/vouch/gmail') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReferencesController::requestGmailReferencesAction',  '_route' => 'request_gmail_references',);
                }

                // request_yahoomail_references
                if ($pathinfo === '/local-author/vouch/yahoo-mail') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReferencesController::requestYahooMailReferencesAction',  '_route' => 'request_yahoomail_references',);
                }

                // buggl_vouch
                if ($pathinfo === '/local-author/vouch') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReferencesController::vouchAction',  '_route' => 'buggl_vouch',);
                }

                // get_yahoomail_access_token
                if ($pathinfo === '/local-author/references/get-yahoo-mail-request-token') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReferencesController::getYahooMailAccessTokenAction',  '_route' => 'get_yahoomail_access_token',);
                }

                // send_local_reference_request
                if ($pathinfo === '/local-author/references/send-request-references') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReferencesController::sendLocalReferenceRequestEmailAction',  '_route' => 'send_local_reference_request',);
                }

                // feature_references
                if ($pathinfo === '/local-author/references/bulk-feature') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReferencesController::featureAction',  '_route' => 'feature_references',);
                }

                // feature_reference_ajax
                if (0 === strpos($pathinfo, '/local-author/references/feature') && preg_match('#^/local\\-author/references/feature/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReferencesController::featureAjaxAction',  '_format' => 'json',)), array('_route' => 'feature_reference_ajax'));
                }

                // unfeature_reference_ajax
                if (0 === strpos($pathinfo, '/local-author/references/unfeature') && preg_match('#^/local\\-author/references/unfeature/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReferencesController::unFeatureAjaxAction',  '_format' => 'json',)), array('_route' => 'unfeature_reference_ajax'));
                }

                // local_author_references
                if (0 === strpos($pathinfo, '/local-author/references') && preg_match('#^/local\\-author/references(?:/(?P<type>[^/]+))?$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReferencesController::indexAction',  'type' => 'list',)), array('_route' => 'local_author_references'));
                }

                // references_pagination
                if (0 === strpos($pathinfo, '/local-author/references-pagination') && preg_match('#^/local\\-author/references\\-pagination(?:/(?P<type>[^/]+))?$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ReferencesController::referencesListAction',  'type' => 'list',)), array('_route' => 'references_pagination'));
                }

                // connect_with_stripe
                if ($pathinfo === '/local-author/connect-with-stripe') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\PaymentsController::connectWithStripeUrlAction',  '_route' => 'connect_with_stripe',);
                }

                // connect_with_stripe_redirect_url
                if ($pathinfo === '/local-author/connect-with-stripe-redirect-url') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\PaymentsController::connectWithStripeRedirectUrlAction',  '_route' => 'connect_with_stripe_redirect_url',);
                }

                // disconnect_stripe
                if ($pathinfo === '/local-author/disconnect-stripe-account') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\PaymentsController::disconnectStripeAction',  '_route' => 'disconnect_stripe',);
                }

                // pay_with_stripe
                if ($pathinfo === '/local-author/pay-with-stripe') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\PaymentsController::paymentWithStripeAction',  '_route' => 'pay_with_stripe',);
                }

                // local_author_eguides
                if (0 === strpos($pathinfo, '/local-author/e-guides') && preg_match('#^/local\\-author/e\\-guides/(?P<status>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EguidesController::indexAction',)), array('_route' => 'local_author_eguides'));
                }

                // local_author_guide_list
                if (preg_match('#^/local\\-author/(?P<slug>[^/]+)/list\\-of\\-guides(?:/(?P<page>[^/]+))?$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EGuideListController::showAction',  'page' => '1',)), array('_route' => 'local_author_guide_list'));
                }

                // e_guide_request_modal
                if ($pathinfo === '/local-author/show-modal') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EGuideRequestController::eGuideRequestModalAction',  '_route' => 'e_guide_request_modal',);
                }

                // e_guide_request_submit
                if ($pathinfo === '/local-author/request-sent') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EGuideRequestController::eGuideRequestSubmitAction',  '_route' => 'e_guide_request_submit',);
                }

                // e_guide_request
                if ($pathinfo === '/local-author/guide-request') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EGuideRequestController::indexAction',  '_route' => 'e_guide_request',);
                }

                // e_guide_view_message
                if (0 === strpos($pathinfo, '/local-author/guide-request') && preg_match('#^/local\\-author/guide\\-request/(?P<slug>[^/]+)/(?P<id>[^/]+)/(?P<type>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EGuideRequestMessageController::ShowMessageAction',)), array('_route' => 'e_guide_view_message'));
                }

                // local_author_messages_eguide_thread
                if (preg_match('#^/local\\-author/(?P<type>[^/]+)/(?P<messageThreadToUserId>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EGuideRequestMessageController::messageThreadAction',)), array('_route' => 'local_author_messages_eguide_thread'));
                }

                // e_guide_reply_message
                if (rtrim($pathinfo, '/') === '/local-author/guide-request') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'e_guide_reply_message');
                    }

                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EGuideRequestMessageController::ReplyMessageAction',  '_route' => 'e_guide_reply_message',);
                }

                // e_guide_share
                if (0 === strpos($pathinfo, '/local-author/share-guide') && preg_match('#^/local\\-author/share\\-guide/(?P<slug>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EGuideShareController::guideShareAction',)), array('_route' => 'e_guide_share'));
                }

                // e_guide_share_facebook
                if (0 === strpos($pathinfo, '/local-author/share-guide-in-facebook') && preg_match('#^/local\\-author/share\\-guide\\-in\\-facebook/(?P<slug>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EGuideShareController::shareInFacebookAction',)), array('_route' => 'e_guide_share_facebook'));
                }

                // e_guide_share_twitter
                if (0 === strpos($pathinfo, '/local-author/share-guide-in-twitter') && preg_match('#^/local\\-author/share\\-guide\\-in\\-twitter/(?P<slug>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EGuideShareController::shareInTwitterAction',)), array('_route' => 'e_guide_share_twitter'));
                }

                // buggl_earn_more
                if ($pathinfo === '/local-author/earn-more') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\EarnMoreController::earnMoreAction',  '_route' => 'buggl_earn_more',);
                }

                // buggl_buy_guide
                if (0 === strpos($pathinfo, '/local-author/buy/buy-guide') && preg_match('#^/local\\-author/buy/buy\\-guide/(?P<eguideId>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\PaymentsController::buyGuideAction',)), array('_route' => 'buggl_buy_guide'));
                }

                // test_fb_post
                if ($pathinfo === '/local-author/test-fb-post/qazxsw') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\SocialMediaController::testFbPostAction',  '_route' => 'test_fb_post',);
                }

                // local_author_profile
                if (preg_match('#^/local\\-author/(?P<slug>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\LocalAuthor\\ProfileController::profileAction',)), array('_route' => 'local_author_profile'));
                }

            }

            if (0 === strpos($pathinfo, '/admin')) {
                // admin_home
                if (rtrim($pathinfo, '/') === '/admin') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', 'admin_home');
                    }

                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\MainController::indexAction',  '_route' => 'admin_home',);
                }

                // admin_ajax_get_city_in_options
                if ($pathinfo === '/admin/ajax-get-city') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AuthorController::getCityAction',  '_route' => 'admin_ajax_get_city_in_options',);
                }

                // admin_activity_interest
                if ($pathinfo === '/admin/activities-and-interests') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\ActivityController::indexAction',  '_route' => 'admin_activity_interest',);
                }

                // admin_activity_interest_unpublished
                if ($pathinfo === '/admin/activities-and-interests/unpublished') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\ActivityController::unpublishedAction',  '_route' => 'admin_activity_interest_unpublished',);
                }

                // admin_activity_interest_custom
                if ($pathinfo === '/admin/activities-and-interests/local-author-added') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\ActivityController::localAuthorAddedAction',  '_route' => 'admin_activity_interest_custom',);
                }

                // admin_activity_publish
                if ($pathinfo === '/admin/activities-and-interest/published') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\ActivityController::publishAction',  '_route' => 'admin_activity_publish',);
                }

                // admin_category_save
                if ($pathinfo === '/admin/save-activity') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\ActivityController::saveActivityAction',  '_route' => 'admin_category_save',);
                }

                // admin_category_add_form
                if ($pathinfo === '/admin/add-category-form') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\ActivityController::addFormAction',  '_route' => 'admin_category_add_form',);
                }

                // admin_trip_theme
                if ($pathinfo === '/admin/trip-themes') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\TripThemeController::indexAction',  '_route' => 'admin_trip_theme',);
                }

                // admin_trip_theme_save
                if ($pathinfo === '/admin/save-trip-themes') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\TripThemeController::saveAction',  '_route' => 'admin_trip_theme_save',);
                }

                // admin_trip_theme_add_form
                if ($pathinfo === '/admin/add-trip-theme-form') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\TripThemeController::addFormAction',  '_route' => 'admin_trip_theme_add_form',);
                }

                // admin_trip_theme_publish
                if (0 === strpos($pathinfo, '/admin/toggle-trip-theme') && preg_match('#^/admin/toggle\\-trip\\-theme/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\TripThemeController::toggleAction',)), array('_route' => 'admin_trip_theme_publish'));
                }

                // admin_travel_guides
                if ($pathinfo === '/admin/travel-guides') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\TravelGuideController::indexAction',  '_route' => 'admin_travel_guides',);
                }

                // admin_travel_guides_active
                if ($pathinfo === '/admin/travel-guides/active') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\TravelGuideController::activeAction',  '_route' => 'admin_travel_guides_active',);
                }

                // admin_travel_guides_archived
                if ($pathinfo === '/admin/travel-guides/archived') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\TravelGuideController::archivedAction',  '_route' => 'admin_travel_guides_archived',);
                }

                // admin_travel_guides_denied
                if ($pathinfo === '/admin/travel-guides/denied') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\TravelGuideController::deniedAction',  '_route' => 'admin_travel_guides_denied',);
                }

                // admin_travel_guide_fetch
                if (0 === strpos($pathinfo, '/admin/travel-guides/fetch') && preg_match('#^/admin/travel\\-guides/fetch\\-(?P<status>[^\\-]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\TravelGuideController::fetchAction',)), array('_route' => 'admin_travel_guide_fetch'));
                }

                // admin_buggl_eguide_overview
                if (preg_match('#^/admin/(?P<slug>[^/]+)/overview$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\TravelGuideController::guideOverviewAction',)), array('_route' => 'admin_buggl_eguide_overview'));
                }

                // admin_buggl_eguide_full
                if (preg_match('#^/admin/(?P<slug>[^/]+)/itinerary$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\TravelGuideController::fullGuideAction',)), array('_route' => 'admin_buggl_eguide_full'));
                }

                // admin_buggl_eguide_secrets
                if (preg_match('#^/admin/(?P<slug>[^/]+)/local\\-secrets/(?P<type>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\TravelGuideController::localPlacesAction',)), array('_route' => 'admin_buggl_eguide_secrets'));
                }

                // admin_buggl_eguide_change_status
                if (0 === strpos($pathinfo, '/admin/eguide/change-status') && preg_match('#^/admin/eguide/change\\-status/(?P<status>[^/\\-]+)\\-(?P<id>[^\\-]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\TravelGuideController::changeStatusAction',)), array('_route' => 'admin_buggl_eguide_change_status'));
                }

                // admin_message_guide
                if ($pathinfo === '/admin/eguide/message') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\TravelGuideController::messageGuideAction',  '_route' => 'admin_message_guide',);
                }

                // admin_locations
                if ($pathinfo === '/admin/locations') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\LocationController::indexAction',  '_route' => 'admin_locations',);
                }

                // admin_locations_country_form
                if ($pathinfo === '/admin/locations/country-form') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\LocationController::getCountryFormAction',  '_route' => 'admin_locations_country_form',);
                }

                // admin_locations_save_country
                if ($pathinfo === '/admin/locations/save-country') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\LocationController::saveCountryAction',  '_route' => 'admin_locations_save_country',);
                }

                // admin_locations_category_form
                if ($pathinfo === '/admin/location/category-form') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\LocationController::getCategoryFormAction',  '_route' => 'admin_locations_category_form',);
                }

                // admin_locations_save_category
                if ($pathinfo === '/admin/locations/save-category') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\LocationController::saveCategoryAction',  '_route' => 'admin_locations_save_category',);
                }

                // admin_location_category_delete
                if ($pathinfo === '/admin/delete-category') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\LocationController::deleteCategoryAction',  '_route' => 'admin_location_category_delete',);
                }

                // admin_locations_save_city
                if ($pathinfo === '/admin/locations/save-city') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\LocationController::saveCityAction',  '_route' => 'admin_locations_save_city',);
                }

                // admin_locations_city_form
                if ($pathinfo === '/admin/location/city-form') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\LocationController::getCityFormAction',  '_route' => 'admin_locations_city_form',);
                }

                // admin_location_fetch_eguide
                if ($pathinfo === '/admin/location/fetch-guides') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\LocationController::fetchGuideByCountryAction',  '_route' => 'admin_location_fetch_eguide',);
                }

                // admin_locations_feature_guide
                if ($pathinfo === '/admin/location/feature-guide') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\LocationController::featureGuideAction',  '_route' => 'admin_locations_feature_guide',);
                }

                // admin_countries
                if ($pathinfo === '/admin/locations/countries') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\LocationController::countryAction',  '_route' => 'admin_countries',);
                }

                // admin_cities
                if ($pathinfo === '/admin/locations/cities') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\LocationController::cityAction',  '_route' => 'admin_cities',);
                }

                // admin_gallery
                if ($pathinfo === '/admin/gallery') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\GalleryController::indexAction',  '_route' => 'admin_gallery',);
                }

                // admin_gallery_fetch_more
                if ($pathinfo === '/admin/fetch-more') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\GalleryController::fetchMoreAction',  '_route' => 'admin_gallery_fetch_more',);
                }

                // admin_author
                if ($pathinfo === '/admin/author') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AuthorController::indexAction',  '_route' => 'admin_author',);
                }

                // admin_local_author_by_city
                if ($pathinfo === '/admin/author-by-city') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AuthorController::getLocalAuthorByCityAction',  '_route' => 'admin_local_author_by_city',);
                }

                // admin_ajax_local_author
                if ($pathinfo === '/admin/search-local-author') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AuthorController::searchLocalAuthorAction',  '_route' => 'admin_ajax_local_author',);
                }

                // admin_suspend_local_author
                if ($pathinfo === '/admin/toggle-suspension') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AuthorController::toggleSuspensionAction',  '_route' => 'admin_suspend_local_author',);
                }

                // admin_view_info
                if (0 === strpos($pathinfo, '/admin/local-author/info') && preg_match('#^/admin/local\\-author/info/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AuthorController::infoAction',)), array('_route' => 'admin_view_info'));
                }

                // admin_spots
                if ($pathinfo === '/admin/spots') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\SpotsController::indexAction',  '_route' => 'admin_spots',);
                }

                // admin_spots_fetch
                if ($pathinfo === '/admin/spots/fetch-more') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\SpotsController::fetchMoreAction',  '_route' => 'admin_spots_fetch',);
                }

                // admin_messages
                if ($pathinfo === '/admin/messages') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\MessagesController::indexAction',  '_route' => 'admin_messages',);
                }

                // admin_fetch_unread
                if ($pathinfo === '/admin/fetch-message-unread') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\MessagesController::fetchUnreadMessageAction',  '_route' => 'admin_fetch_unread',);
                }

                // admin_mark_as_read_message
                if ($pathinfo === '/admin/message/mark-as-read') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\MessagesController::markAsReadAction',  '_route' => 'admin_mark_as_read_message',);
                }

                // admin_local_request_message
                if ($pathinfo === '/admin/messages/localrequests') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\MessagesController::fetchLocalRequestMessageAction',  '_route' => 'admin_local_request_message',);
                }

                // admin_local_request_message_approve
                if (0 === strpos($pathinfo, '/admin/messages/localrequests') && preg_match('#^/admin/messages/localrequests/(?P<country>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\MessagesController::updateLocalRequestMessageAction',)), array('_route' => 'admin_local_request_message_approve'));
                }

                // admin_guide_request_message
                if ($pathinfo === '/admin/messages/guide-request') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\MessagesController::guideRequestAction',  '_route' => 'admin_guide_request_message',);
                }

                // admin_contact_us
                if ($pathinfo === '/admin/messages/contact-us') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\MessagesController::contactUsAction',  '_route' => 'admin_contact_us',);
                }

                // admin_fetch_contact_us
                if ($pathinfo === '/admin/messages/fetch-contact-us') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\MessagesController::fetchContactUsAction',  '_route' => 'admin_fetch_contact_us',);
                }

                // admin_content
                if ($pathinfo === '/admin/content') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\ContentController::indexAction',  '_route' => 'admin_content',);
                }

                // admin_content_add
                if ($pathinfo === '/admin/content/add') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\ContentController::addAction',  '_route' => 'admin_content_add',);
                }

                // admin_content_edit
                if (0 === strpos($pathinfo, '/admin/content/edit') && preg_match('#^/admin/content/edit/(?P<contentId>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\ContentController::EditAction',)), array('_route' => 'admin_content_edit'));
                }

                // admin_content_delete
                if (0 === strpos($pathinfo, '/admin/content/delete') && preg_match('#^/admin/content/delete/(?P<contentId>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\ContentController::deleteAction',)), array('_route' => 'admin_content_delete'));
                }

                // admin_ad
                if ($pathinfo === '/admin/ad') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AdController::indexAction',  '_route' => 'admin_ad',);
                }

                // admin_ad_feature_guide
                if ($pathinfo === '/admin/ad/feature-guide') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AdController::featureAdAction',  '_route' => 'admin_ad_feature_guide',);
                }

                // admin_ad_feature_guide_temp_photo
                if ($pathinfo === '/admin/ad/process-feature-guide-temp-photo') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AdController::processTempPhotoAction',  '_route' => 'admin_ad_feature_guide_temp_photo',);
                }

                // admin_ad_feature_guide_process
                if ($pathinfo === '/admin/ad/process-feature-guide') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AdController::processFeatureGuideAction',  '_route' => 'admin_ad_feature_guide_process',);
                }

                // admin_spotlight
                if ($pathinfo === '/admin/ad/spotlight') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AdController::spotLightAction',  '_route' => 'admin_spotlight',);
                }

                // remove_spotlight
                if ($pathinfo === '/admin/ad/remove-spotlight') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AdController::removeSpotLightAction',  '_route' => 'remove_spotlight',);
                }

                // admin_featured
                if ($pathinfo === '/admin/ad/featured') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AdController::featuredAction',  '_route' => 'admin_featured',);
                }

                // admin_unfeature
                if (0 === strpos($pathinfo, '/admin/ad/unfeature') && preg_match('#^/admin/ad/unfeature/(?P<guideId>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AdController::unfeatureAction',)), array('_route' => 'admin_unfeature'));
                }

                // admin_ajax_get_guide_info
                if ($pathinfo === '/admin/ajax-get-guide-info') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AdController::getGuideInfoAction',  '_route' => 'admin_ajax_get_guide_info',);
                }

                // admin_get_rotating_photo_form
                if ($pathinfo === '/admin/ajax-get-rotating-photo-form') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AdController::getRotatingFeatureFormAction',  '_route' => 'admin_get_rotating_photo_form',);
                }

                // admin_rotating_feature_delete
                if (0 === strpos($pathinfo, '/admin/ad/rotating-feature-delete') && preg_match('#^/admin/ad/rotating\\-feature\\-delete/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\AdController::deleteRotatingFeatureAction',)), array('_route' => 'admin_rotating_feature_delete'));
                }

                // admin_dashboard
                if ($pathinfo === '/admin/dashboard') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\DashboardController::indexAction',  '_route' => 'admin_dashboard',);
                }

                // admin_eguide_ranking
                if ($pathinfo === '/admin/dashboard/eguide-ranking') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\DashboardController::eguideRankAction',  '_route' => 'admin_eguide_ranking',);
                }

                // admin_user_ranking
                if ($pathinfo === '/admin/dashboard/user-ranking') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\DashboardController::userRankAction',  '_route' => 'admin_user_ranking',);
                }

                // admin_billing
                if ($pathinfo === '/admin/billing') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\BillingController::indexAction',  '_route' => 'admin_billing',);
                }

                // admin_billing_search
                if ($pathinfo === '/admin/billing/search') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\BillingController::searchAction',  '_route' => 'admin_billing_search',);
                }

                // admin_login
                if ($pathinfo === '/admin/login') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\LoginController::loginAction',  '_route' => 'admin_login',);
                }

                // admin_login_check
                if ($pathinfo === '/admin/login_check') {
                    return array('_route' => 'admin_login_check');
                }

                // admin_logout
                if ($pathinfo === '/admin/logout') {
                    return array('_route' => 'admin_logout');
                }

                // admin_unfeatured_guide
                if ($pathinfo === '/admin/get-unfeatured-guides') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\TravelGuideController::featuredAction',  '_route' => 'admin_unfeatured_guide',);
                }

                // admin_beta_invite_list
                if (0 === strpos($pathinfo, '/admin/beta-invites') && preg_match('#^/admin/beta\\-invites/(?P<type>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\BetaInviteController::indexAction',)), array('_route' => 'admin_beta_invite_list'));
                }

                // admin_beta_invite
                if ($pathinfo === '/admin/invite-for-beta') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\BetaInviteController::inviteAction',  '_route' => 'admin_beta_invite',);
                }

                // admin_beta_invite_pagination
                if (0 === strpos($pathinfo, '/admin/beta-invites-list') && preg_match('#^/admin/beta\\-invites\\-list/(?P<type>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\BetaInviteController::inviteListAction',)), array('_route' => 'admin_beta_invite_pagination'));
                }

                // admin_beta_invite_resend
                if (0 === strpos($pathinfo, '/admin/beta-invite/resend') && preg_match('#^/admin/beta\\-invite/resend/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\BetaInviteController::resendAction',)), array('_route' => 'admin_beta_invite_resend'));
                }

                // admin_beta_invite_delete
                if (0 === strpos($pathinfo, '/admin/beta-invite/delete') && preg_match('#^/admin/beta\\-invite/delete/(?P<id>[^/]+)$#s', $pathinfo, $matches)) {
                    return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\BetaInviteController::deleteAction',)), array('_route' => 'admin_beta_invite_delete'));
                }

                // admin_export_local_author_to_csv
                if ($pathinfo === '/admin/export-local-author-to-csv') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\ExportToCsvController::authorToCsvAction',  '_route' => 'admin_export_local_author_to_csv',);
                }

                // admin_export_guide_to_csv
                if ($pathinfo === '/admin/export-guide-to-csv') {
                    return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Admin\\ExportToCsvController::guideToCsvAction',  '_route' => 'admin_export_guide_to_csv',);
                }

            }

            // buggl_beta_login
            if ($pathinfo === '/beta-login') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\BetaController::betaLoginAction',  '_route' => 'buggl_beta_login',);
            }

            // registration
            if ($pathinfo === '/registration') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\RegistrationController::indexAction',  '_route' => 'registration',);
            }

            // registration_via_modal
            if ($pathinfo === '/registration-via-modal') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\RegistrationController::registrationModalAction',  '_route' => 'registration_via_modal',);
            }

            // registration_facebook_redirect
            if ($pathinfo === '/registration-via-facebook-redirect-url') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\RegistrationController::registrationViaFacebookRedirectAction',  '_route' => 'registration_facebook_redirect',);
            }

            // registration_facebook
            if ($pathinfo === '/registration-via-facebook') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\RegistrationController::registrationViaFacebookAction',  '_route' => 'registration_facebook',);
            }

            // registration_twitter_redirect
            if ($pathinfo === '/registration-via-twitter-redirect-url') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\RegistrationController::registrationViaTwitterRedirectAction',  '_route' => 'registration_twitter_redirect',);
            }

            // registration_twitter
            if ($pathinfo === '/registration-via-twitter') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\RegistrationController::registrationViaTwitterAction',  '_route' => 'registration_twitter',);
            }

            // registration_google_plus_redirect
            if ($pathinfo === '/registration-via-google-redirect-url') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\RegistrationController::registrationViaGooglePlusRedirectAction',  '_route' => 'registration_google_plus_redirect',);
            }

            // registration_google_plus
            if ($pathinfo === '/registration-via-google') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\RegistrationController::registrationViaGooglePlusAction',  '_route' => 'registration_google_plus',);
            }

            // registration_success
            if (0 === strpos($pathinfo, '/registration-success') && preg_match('#^/registration\\-success/(?P<email>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\RegistrationController::registrationSuccessAction',)), array('_route' => 'registration_success'));
            }

            // registration_confirmed_success
            if (0 === strpos($pathinfo, '/registration-confirmed-success') && preg_match('#^/registration\\-confirmed\\-success/(?P<referer>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\RegistrationController::registrationConfirmedSuccessAction',)), array('_route' => 'registration_confirmed_success'));
            }

            // registration_confirm
            if (0 === strpos($pathinfo, '/registration-confirmation') && preg_match('#^/registration\\-confirmation/(?P<token>[^/]+)/(?P<email>[^/]+)/(?P<referer>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\RegistrationController::registrationConfirmationAction',)), array('_route' => 'registration_confirm'));
            }

            // registration_confirm_resend
            if ($pathinfo === '/resend-registration-confirmation') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\RegistrationController::resendConfirmationAction',  '_route' => 'registration_confirm_resend',);
            }

            // registration_confirm_fail
            if ($pathinfo === '/registration-confirmation-failed') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\RegistrationController::registrationConfirmationFailAction',  '_route' => 'registration_confirm_fail',);
            }

            // registration_confirm_success
            if ($pathinfo === '/registration-confirmation-success') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\RegistrationController::registrationConfirmationSuccessAction',  '_route' => 'registration_confirm_success',);
            }

            // registration_confirm_already_verified
            if ($pathinfo === '/registration-confirmation-already-verified') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\RegistrationController::registrationConfirmationAlreadyVerifiedAction',  '_route' => 'registration_confirm_already_verified',);
            }

            // login_via_facebook_url
            if ($pathinfo === '/login-via-facebook') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\MainController::loginViaFacebookAction',  '_route' => 'login_via_facebook_url',);
            }

            // signup_via_facebook_url
            if ($pathinfo === '/sign-up-via-facebook') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\MainController::loginViaFacebookAction',  '_route' => 'signup_via_facebook_url',);
            }

            // login_via_twitter_url
            if ($pathinfo === '/login-via-twitter') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\MainController::loginViaTwitterAction',  '_route' => 'login_via_twitter_url',);
            }

            // signup_via_twitter_url
            if ($pathinfo === '/sign-up-via-twitter') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\MainController::loginViaTwitterAction',  '_route' => 'signup_via_twitter_url',);
            }

            // login_via_google_plus_url
            if ($pathinfo === '/login-via-google') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\MainController::loginViaGooglePlusAction',  '_route' => 'login_via_google_plus_url',);
            }

            // signup_via_google_plus_url
            if ($pathinfo === '/sign-up-via-google') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\MainController::loginViaGooglePlusAction',  '_route' => 'signup_via_google_plus_url',);
            }

            // buggl_password_reset_request
            if ($pathinfo === '/password-reset') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\PasswordResetController::indexAction',  '_route' => 'buggl_password_reset_request',);
            }

            // buggl_password_reset_request_resend
            if (0 === strpos($pathinfo, '/resend-password-reset-request') && preg_match('#^/resend\\-password\\-reset\\-request/(?P<requestId>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\PasswordResetController::resendRequestAction',)), array('_route' => 'buggl_password_reset_request_resend'));
            }

            // buggl_password_reset_request_sent
            if (0 === strpos($pathinfo, '/password-reset-sent') && preg_match('#^/password\\-reset\\-sent/(?P<requestId>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\PasswordResetController::resetPasswordRequestSentAction',)), array('_route' => 'buggl_password_reset_request_sent'));
            }

            // buggl_password_reset
            if (0 === strpos($pathinfo, '/reset-my-password') && preg_match('#^/reset\\-my\\-password/(?P<token>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\PasswordResetController::resetPasswordAction',)), array('_route' => 'buggl_password_reset'));
            }

            // buggl_password_reset_invalid
            if ($pathinfo === '/invalid-password-reset-token') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\PasswordResetController::invalidTokenAction',  '_route' => 'buggl_password_reset_invalid',);
            }

            // reference_request_response
            if (0 === strpos($pathinfo, '/answer-reference-request') && preg_match('#^/answer\\-reference\\-request/(?P<token>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\ReferencesFrontendController::indexAction',)), array('_route' => 'reference_request_response'));
            }

            // reference_saved
            if ($pathinfo === '/reference-saved') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\ReferencesFrontendController::referenceSavedAction',  '_route' => 'reference_saved',);
            }

            // reference_request_invalid
            if ($pathinfo === '/invalid-reference-request') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\ReferencesFrontendController::invalidTokenAction',  '_route' => 'reference_request_invalid',);
            }

            // login
            if ($pathinfo === '/login') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\MainController::loginAction',  '_route' => 'login',);
            }

            // login_check
            if ($pathinfo === '/login_check') {
                return array('_route' => 'login_check');
            }

            // logout
            if ($pathinfo === '/logout') {
                return array('_route' => 'logout');
            }

            // stripe_revoke_access_webhook
            if ($pathinfo === '/revoke-stripe-access') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\MainController::revokeStripeAccessAction',  '_route' => 'stripe_revoke_access_webhook',);
            }

            // request_beta_token
            if ($pathinfo === '/request-beta-token') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\BetaController::requestTokenAction',  '_route' => 'request_beta_token',);
            }

            // get_country_cities
            if ($pathinfo === '/ajax/fetch-country-cities') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Ajax\\AjaxController::getCountryCitiesAction',  '_format' => 'json',  '_route' => 'get_country_cities',);
            }

            // get_country_categories
            if ($pathinfo === '/ajax/fetch-country-categories') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Ajax\\AjaxController::getCountryCategoriesAction',  '_format' => 'json',  '_route' => 'get_country_categories',);
            }

            // get_spot_categories
            if ($pathinfo === '/ajax/fetch-spot-categories') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Ajax\\AjaxController::getSpotCategoriesAction',  '_format' => 'json',  '_route' => 'get_spot_categories',);
            }

            // get_spot_likes
            if ($pathinfo === '/ajax/fetch-spot-likes') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Ajax\\AjaxController::getSpotLikesAction',  '_format' => 'json',  '_route' => 'get_spot_likes',);
            }

            // get_country_list
            if ($pathinfo === '/ajax/fetch-buggl-countries') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Ajax\\AjaxController::getCountryListAction',  '_format' => 'json',  '_route' => 'get_country_list',);
            }

            // get_request_country_list
            if ($pathinfo === '/ajax/fetch-buggl-countries-new') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Ajax\\AjaxController::getRequestCountryListAction',  '_format' => 'json',  '_route' => 'get_request_country_list',);
            }

            // get_interests
            if ($pathinfo === '/ajax/fetch-interests') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Ajax\\AjaxController::getInterestsAction',  '_format' => 'json',  '_route' => 'get_interests',);
            }

            // google_custom_search
            if ($pathinfo === '/ajax/google-custom-search') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Ajax\\AjaxController::googleCustomSearchAction',  '_format' => 'json',  '_route' => 'google_custom_search',);
            }

            // check_country_list
            if ($pathinfo === '/ajax/check-buggl-countries') {
                return array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Ajax\\AjaxController::checkCountryAction',  '_format' => 'json',  '_route' => 'check_country_list',);
            }

            // buggl_eguide_full
            if (preg_match('#^/(?P<slug>[^/]+)/itinerary$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\GuideController::fullGuideAction',)), array('_route' => 'buggl_eguide_full'));
            }

            // buggl_eguide_secrets
            if (preg_match('#^/(?P<slug>[^/]+)/local\\-secrets(?:/(?P<type>[^/]+))?$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\GuideController::localPlacesAction',  'type' => 'all',)), array('_route' => 'buggl_eguide_secrets'));
            }

            // buggl_eguide_overview
            if (preg_match('#^/(?P<slug>[^/]+)/overview$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\GuideController::guideOverviewAction',)), array('_route' => 'buggl_eguide_overview'));
            }

            // buggl_eguide_spot
            if (preg_match('#^/(?P<slug>[^/]+)/(?P<spotSlug>[^/]+)/(?P<spotDetailId>[^/]+)$#s', $pathinfo, $matches)) {
                return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Buggl\\MainBundle\\Controller\\Frontend\\GuideController::spotAction',)), array('_route' => 'buggl_eguide_spot'));
            }

        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}

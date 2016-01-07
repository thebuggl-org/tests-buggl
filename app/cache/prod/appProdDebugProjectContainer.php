<?php

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;

/**
 * appProdDebugProjectContainer
 *
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 */
class appProdDebugProjectContainer extends Container
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->parameters = $this->getDefaultParameters();

        $this->services =
        $this->scopedServices =
        $this->scopeStacks = array();

        $this->set('service_container', $this);

        $this->scopes = array('request' => 'container');
        $this->scopeChildren = array('request' => array());
    }

    /**
     * Gets the 'annotation_reader' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Doctrine\Common\Annotations\FileCacheReader A Doctrine\Common\Annotations\FileCacheReader instance.
     */
    protected function getAnnotationReaderService()
    {
        return $this->services['annotation_reader'] = new \Doctrine\Common\Annotations\FileCacheReader(new \Doctrine\Common\Annotations\AnnotationReader(), 'C:/xampp/htdocs/beta-buggl/app/cache/prod/annotations', true);
    }

    /**
     * Gets the 'assetic.asset_manager' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Assetic\Factory\LazyAssetManager A Assetic\Factory\LazyAssetManager instance.
     */
    protected function getAssetic_AssetManagerService()
    {
        $a = $this->get('templating.loader');

        $this->services['assetic.asset_manager'] = $instance = new \Assetic\Factory\LazyAssetManager($this->get('assetic.asset_factory'), array('twig' => new \Assetic\Factory\Loader\CachedFormulaLoader(new \Assetic\Extension\Twig\TwigFormulaLoader($this->get('twig')), new \Assetic\Cache\ConfigCache('C:/xampp/htdocs/beta-buggl/app/cache/prod/assetic/config'), true)));

        $instance->addResource(new \Symfony\Bundle\AsseticBundle\Factory\Resource\CoalescingDirectoryResource(array(0 => new \Symfony\Bundle\AsseticBundle\Factory\Resource\DirectoryResource($a, 'BugglMainBundle', 'C:/xampp/htdocs/beta-buggl/app/Resources/BugglMainBundle/views', '/\\.[^.]+\\.twig$/'), 1 => new \Symfony\Bundle\AsseticBundle\Factory\Resource\DirectoryResource($a, 'BugglMainBundle', 'C:\\xampp\\htdocs\\beta-buggl\\src\\Buggl\\MainBundle/Resources/views', '/\\.[^.]+\\.twig$/'))), 'twig');
        $instance->addResource(new \Symfony\Bundle\AsseticBundle\Factory\Resource\CoalescingDirectoryResource(array(0 => new \Symfony\Bundle\AsseticBundle\Factory\Resource\DirectoryResource($a, 'BugglPhotoBundle', 'C:/xampp/htdocs/beta-buggl/app/Resources/BugglPhotoBundle/views', '/\\.[^.]+\\.twig$/'), 1 => new \Symfony\Bundle\AsseticBundle\Factory\Resource\DirectoryResource($a, 'BugglPhotoBundle', 'C:\\xampp\\htdocs\\beta-buggl\\src\\Buggl\\PhotoBundle/Resources/views', '/\\.[^.]+\\.twig$/'))), 'twig');
        $instance->addResource(new \Symfony\Bundle\AsseticBundle\Factory\Resource\DirectoryResource($a, '', 'C:/xampp/htdocs/beta-buggl/app/Resources/views', '/\\.[^.]+\\.twig$/'), 'twig');

        return $instance;
    }

    /**
     * Gets the 'assetic.controller' service.
     *
     * @return Symfony\Bundle\AsseticBundle\Controller\AsseticController A Symfony\Bundle\AsseticBundle\Controller\AsseticController instance.
     */
    protected function getAssetic_ControllerService()
    {
        return new \Symfony\Bundle\AsseticBundle\Controller\AsseticController($this->get('request'), $this->get('assetic.asset_manager'), $this->get('assetic.cache'), false, NULL);
    }

    /**
     * Gets the 'assetic.filter.cssrewrite' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Assetic\Filter\CssRewriteFilter A Assetic\Filter\CssRewriteFilter instance.
     */
    protected function getAssetic_Filter_CssrewriteService()
    {
        return $this->services['assetic.filter.cssrewrite'] = new \Assetic\Filter\CssRewriteFilter();
    }

    /**
     * Gets the 'assetic.filter.yui_css' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Assetic\Filter\Yui\CssCompressorFilter A Assetic\Filter\Yui\CssCompressorFilter instance.
     */
    protected function getAssetic_Filter_YuiCssService()
    {
        $this->services['assetic.filter.yui_css'] = $instance = new \Assetic\Filter\Yui\CssCompressorFilter('C:/xampp/htdocs/beta-buggl/app/Resources/java/yuicompressor-2.4.7.jar', 'C:\\ProgramData\\Oracle\\Java\\javapath\\java.EXE');

        $instance->setCharset('UTF-8');
        $instance->setTimeout(NULL);
        $instance->setStackSize(NULL);
        $instance->setLineBreak(NULL);

        return $instance;
    }

    /**
     * Gets the 'assetic.filter.yui_js' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Assetic\Filter\Yui\JsCompressorFilter A Assetic\Filter\Yui\JsCompressorFilter instance.
     */
    protected function getAssetic_Filter_YuiJsService()
    {
        $this->services['assetic.filter.yui_js'] = $instance = new \Assetic\Filter\Yui\JsCompressorFilter('C:/xampp/htdocs/beta-buggl/app/Resources/java/yuicompressor-2.4.7.jar', 'C:\\ProgramData\\Oracle\\Java\\javapath\\java.EXE');

        $instance->setCharset('UTF-8');
        $instance->setTimeout(NULL);
        $instance->setStackSize(NULL);
        $instance->setNomunge(NULL);
        $instance->setPreserveSemi(NULL);
        $instance->setDisableOptimizations(NULL);
        $instance->setLineBreak(NULL);

        return $instance;
    }

    /**
     * Gets the 'assetic.filter_manager' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\AsseticBundle\FilterManager A Symfony\Bundle\AsseticBundle\FilterManager instance.
     */
    protected function getAssetic_FilterManagerService()
    {
        return $this->services['assetic.filter_manager'] = new \Symfony\Bundle\AsseticBundle\FilterManager($this, array('cloudfront_filter' => 'buggl.assetic.custom_filter', 'cssrewrite' => 'assetic.filter.cssrewrite', 'yui_css' => 'assetic.filter.yui_css', 'yui_js' => 'assetic.filter.yui_js'));
    }

    /**
     * Gets the 'assetic.request_listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\AsseticBundle\EventListener\RequestListener A Symfony\Bundle\AsseticBundle\EventListener\RequestListener instance.
     */
    protected function getAssetic_RequestListenerService()
    {
        return $this->services['assetic.request_listener'] = new \Symfony\Bundle\AsseticBundle\EventListener\RequestListener();
    }

    /**
     * Gets the 'buggl.assetic.custom_filter' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\CloudFrontAssetsService A Buggl\MainBundle\Service\CloudFrontAssetsService instance.
     */
    protected function getBuggl_Assetic_CustomFilterService()
    {
        return $this->services['buggl.assetic.custom_filter'] = new \Buggl\MainBundle\Service\CloudFrontAssetsService();
    }

    /**
     * Gets the 'buggl.authenticate.action_listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\EventListener\SecurePageListener A Buggl\MainBundle\EventListener\SecurePageListener instance.
     */
    protected function getBuggl_Authenticate_ActionListenerService()
    {
        return $this->services['buggl.authenticate.action_listener'] = new \Buggl\MainBundle\EventListener\SecurePageListener($this->get('security.context'));
    }

    /**
     * Gets the 'buggl.twig.buggl_activity_helper_extension' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Twig\ActivityHelperExtension A Buggl\MainBundle\Twig\ActivityHelperExtension instance.
     */
    protected function getBuggl_Twig_BugglActivityHelperExtensionService()
    {
        return $this->services['buggl.twig.buggl_activity_helper_extension'] = new \Buggl\MainBundle\Twig\ActivityHelperExtension($this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.constants'), $this->get('router'));
    }

    /**
     * Gets the 'buggl.twig.buggl_cdn_extension' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Twig\BugglCdnExtension A Buggl\MainBundle\Twig\BugglCdnExtension instance.
     */
    protected function getBuggl_Twig_BugglCdnExtensionService()
    {
        return $this->services['buggl.twig.buggl_cdn_extension'] = new \Buggl\MainBundle\Twig\BugglCdnExtension($this);
    }

    /**
     * Gets the 'buggl.twig.buggl_constant_extension' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Twig\BugglConstantExtension A Buggl\MainBundle\Twig\BugglConstantExtension instance.
     */
    protected function getBuggl_Twig_BugglConstantExtensionService()
    {
        return $this->services['buggl.twig.buggl_constant_extension'] = new \Buggl\MainBundle\Twig\BugglConstantExtension($this->get('buggl_main.constants'));
    }

    /**
     * Gets the 'buggl.twig.buggl_css_selected_extension' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Twig\BugglCssSelectedExtension A Buggl\MainBundle\Twig\BugglCssSelectedExtension instance.
     */
    protected function getBuggl_Twig_BugglCssSelectedExtensionService()
    {
        return $this->services['buggl.twig.buggl_css_selected_extension'] = new \Buggl\MainBundle\Twig\BugglCssSelectedExtension();
    }

    /**
     * Gets the 'buggl.twig.buggl_date_helper_extension' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Twig\DateHelperExtension A Buggl\MainBundle\Twig\DateHelperExtension instance.
     */
    protected function getBuggl_Twig_BugglDateHelperExtensionService()
    {
        return $this->services['buggl.twig.buggl_date_helper_extension'] = new \Buggl\MainBundle\Twig\DateHelperExtension();
    }

    /**
     * Gets the 'buggl.twig.buggl_eguide_helper_extension' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Twig\EguideHelperExtension A Buggl\MainBundle\Twig\EguideHelperExtension instance.
     */
    protected function getBuggl_Twig_BugglEguideHelperExtensionService()
    {
        return $this->services['buggl.twig.buggl_eguide_helper_extension'] = new \Buggl\MainBundle\Twig\EguideHelperExtension($this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.eguide_and_spots_native_query'));
    }

    /**
     * Gets the 'buggl.twig.buggl_image_helper_extension' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Twig\ImageHelperExtension A Buggl\MainBundle\Twig\ImageHelperExtension instance.
     */
    protected function getBuggl_Twig_BugglImageHelperExtensionService()
    {
        return $this->services['buggl.twig.buggl_image_helper_extension'] = new \Buggl\MainBundle\Twig\ImageHelperExtension();
    }

    /**
     * Gets the 'buggl.twig.buggl_message_helper_extension' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Twig\MessageHelperExtension A Buggl\MainBundle\Twig\MessageHelperExtension instance.
     */
    protected function getBuggl_Twig_BugglMessageHelperExtensionService()
    {
        return $this->services['buggl.twig.buggl_message_helper_extension'] = new \Buggl\MainBundle\Twig\MessageHelperExtension($this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.constants'));
    }

    /**
     * Gets the 'buggl.twig.buggl_query_extension' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Twig\BugglQueryExtension A Buggl\MainBundle\Twig\BugglQueryExtension instance.
     */
    protected function getBuggl_Twig_BugglQueryExtensionService()
    {
        return $this->services['buggl.twig.buggl_query_extension'] = new \Buggl\MainBundle\Twig\BugglQueryExtension($this->get('buggl_main.entity_repository'), $this->get('buggl_main.rating'));
    }

    /**
     * Gets the 'buggl.twig.buggl_slugify_extension' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Twig\BugglSlugifyExtension A Buggl\MainBundle\Twig\BugglSlugifyExtension instance.
     */
    protected function getBuggl_Twig_BugglSlugifyExtensionService()
    {
        return $this->services['buggl.twig.buggl_slugify_extension'] = new \Buggl\MainBundle\Twig\BugglSlugifyExtension($this->get('buggl_main.slugifier'));
    }

    /**
     * Gets the 'buggl.twig.buggl_string_helper_extension' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Twig\StringHelperExtension A Buggl\MainBundle\Twig\StringHelperExtension instance.
     */
    protected function getBuggl_Twig_BugglStringHelperExtensionService()
    {
        return $this->services['buggl.twig.buggl_string_helper_extension'] = new \Buggl\MainBundle\Twig\StringHelperExtension();
    }

    /**
     * Gets the 'buggl.twig.buggl_travel_info_extension' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Twig\TravelInfoExtension A Buggl\MainBundle\Twig\TravelInfoExtension instance.
     */
    protected function getBuggl_Twig_BugglTravelInfoExtensionService()
    {
        return $this->services['buggl.twig.buggl_travel_info_extension'] = new \Buggl\MainBundle\Twig\TravelInfoExtension();
    }

    /**
     * Gets the 'buggl_aws.asset_dump' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Helper\BugglAssetsDumpHelper A Buggl\MainBundle\Helper\BugglAssetsDumpHelper instance.
     */
    protected function getBugglAws_AssetDumpService()
    {
        return $this->services['buggl_aws.asset_dump'] = new \Buggl\MainBundle\Helper\BugglAssetsDumpHelper('C:/xampp/htdocs/beta-buggl/app/../src/Buggl/MainBundle/Resources/public/');
    }

    /**
     * Gets the 'buggl_aws.wrapper' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Helper\BugglS3Helper A Buggl\MainBundle\Helper\BugglS3Helper instance.
     */
    protected function getBugglAws_WrapperService()
    {
        return $this->services['buggl_aws.wrapper'] = new \Buggl\MainBundle\Helper\BugglS3Helper();
    }

    /**
     * Gets the 'buggl_mail.export_guides_to_csv' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\ExportGuideToCsvService A Buggl\MainBundle\Service\ExportGuideToCsvService instance.
     */
    protected function getBugglMail_ExportGuidesToCsvService()
    {
        return $this->services['buggl_mail.export_guides_to_csv'] = new \Buggl\MainBundle\Service\ExportGuideToCsvService($this->get('mailer'), $this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.constants'), $this->get('router'), 'C:/xampp/htdocs/beta-buggl/app');
    }

    /**
     * Gets the 'buggl_mail.export_local_author_to_csv' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\ExportLocalAuthorToCsvService A Buggl\MainBundle\Service\ExportLocalAuthorToCsvService instance.
     */
    protected function getBugglMail_ExportLocalAuthorToCsvService()
    {
        return $this->services['buggl_mail.export_local_author_to_csv'] = new \Buggl\MainBundle\Service\ExportLocalAuthorToCsvService($this->get('mailer'), $this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.constants'), $this->get('router'), 'C:/xampp/htdocs/beta-buggl/app');
    }

    /**
     * Gets the 'buggl_main.account_notification_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\AccountNotificationService A Buggl\MainBundle\Service\AccountNotificationService instance.
     */
    protected function getBugglMain_AccountNotificationServiceService()
    {
        return $this->services['buggl_main.account_notification_service'] = new \Buggl\MainBundle\Service\AccountNotificationService($this->get('mailer'), $this->get('templating'), $this->get('buggl_main.slugifier'), $this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.constants'), $this->get('router'));
    }

    /**
     * Gets the 'buggl_main.activity_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\ActivityService A Buggl\MainBundle\Service\ActivityService instance.
     */
    protected function getBugglMain_ActivityServiceService()
    {
        return $this->services['buggl_main.activity_service'] = new \Buggl\MainBundle\Service\ActivityService($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.admin_login_handler' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Handler\AdminLoginHandler A Buggl\MainBundle\Handler\AdminLoginHandler instance.
     */
    protected function getBugglMain_AdminLoginHandlerService()
    {
        return $this->services['buggl_main.admin_login_handler'] = new \Buggl\MainBundle\Handler\AdminLoginHandler($this->get('router'), $this->get('security.context'));
    }

    /**
     * Gets the 'buggl_main.admin_stats' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\AdminStatsService A Buggl\MainBundle\Service\AdminStatsService instance.
     */
    protected function getBugglMain_AdminStatsService()
    {
        return $this->services['buggl_main.admin_stats'] = new \Buggl\MainBundle\Service\AdminStatsService($this->get('buggl_main.stats_native_query'));
    }

    /**
     * Gets the 'buggl_main.beta_invite_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\BetaInviteService A Buggl\MainBundle\Service\BetaInviteService instance.
     */
    protected function getBugglMain_BetaInviteServiceService()
    {
        return $this->services['buggl_main.beta_invite_service'] = new \Buggl\MainBundle\Service\BetaInviteService($this->get('mailer'), $this->get('templating'), $this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.constants'), $this->get('router'));
    }

    /**
     * Gets the 'buggl_main.bit_url' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\BitUrlService A Buggl\MainBundle\Service\BitUrlService instance.
     */
    protected function getBugglMain_BitUrlService()
    {
        return $this->services['buggl_main.bit_url'] = new \Buggl\MainBundle\Service\BitUrlService($this->get('doctrine.orm.default_entity_manager'), $this->get('router'));
    }

    /**
     * Gets the 'buggl_main.breadcrumbs' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\BreadcrumbService A Buggl\MainBundle\Service\BreadcrumbService instance.
     */
    protected function getBugglMain_BreadcrumbsService()
    {
        return $this->services['buggl_main.breadcrumbs'] = new \Buggl\MainBundle\Service\BreadcrumbService($this->get('buggl_main.breadcrumbs_factory'));
    }

    /**
     * Gets the 'buggl_main.breadcrumbs_factory' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Factory\BreadcrumbsFactory A Buggl\MainBundle\Factory\BreadcrumbsFactory instance.
     */
    protected function getBugglMain_BreadcrumbsFactoryService()
    {
        return $this->services['buggl_main.breadcrumbs_factory'] = new \Buggl\MainBundle\Factory\BreadcrumbsFactory();
    }

    /**
     * Gets the 'buggl_main.buggl_payment_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\BugglPaymentService A Buggl\MainBundle\Service\BugglPaymentService instance.
     */
    protected function getBugglMain_BugglPaymentServiceService()
    {
        return $this->services['buggl_main.buggl_payment_service'] = new \Buggl\MainBundle\Service\BugglPaymentService($this->get('buggl_main.paypal_service'), $this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.constants'), $this->get('buggl_main.environment_variables'), $this->get('router'), $this->get('buggl_main.purchase_mailer_service'));
    }

    /**
     * Gets the 'buggl_main.category' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\CategoryService A Buggl\MainBundle\Service\CategoryService instance.
     */
    protected function getBugglMain_CategoryService()
    {
        return $this->services['buggl_main.category'] = new \Buggl\MainBundle\Service\CategoryService($this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.constants'), $this->get('event_dispatcher'));
    }

    /**
     * Gets the 'buggl_main.category_photo' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\BugglCategoryPhotoService A Buggl\MainBundle\Service\BugglCategoryPhotoService instance.
     */
    protected function getBugglMain_CategoryPhotoService()
    {
        return $this->services['buggl_main.category_photo'] = new \Buggl\MainBundle\Service\BugglCategoryPhotoService($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.constants' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Helper\BugglConstant A Buggl\MainBundle\Helper\BugglConstant instance.
     */
    protected function getBugglMain_ConstantsService()
    {
        return $this->services['buggl_main.constants'] = new \Buggl\MainBundle\Helper\BugglConstant();
    }

    /**
     * Gets the 'buggl_main.custom_exception' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\EventListener\ExceptionListener A Buggl\MainBundle\EventListener\ExceptionListener instance.
     */
    protected function getBugglMain_CustomExceptionService()
    {
        return $this->services['buggl_main.custom_exception'] = new \Buggl\MainBundle\EventListener\ExceptionListener($this->get('templating'));
    }

    /**
     * Gets the 'buggl_main.eguide_and_spots_native_query' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\EguideAndSpotsNativeQuery A Buggl\MainBundle\Service\EguideAndSpotsNativeQuery instance.
     */
    protected function getBugglMain_EguideAndSpotsNativeQueryService()
    {
        return $this->services['buggl_main.eguide_and_spots_native_query'] = new \Buggl\MainBundle\Service\EguideAndSpotsNativeQuery($this->get('doctrine.dbal.default_connection'));
    }

    /**
     * Gets the 'buggl_main.eguide_request_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\EGuideRequestEmailService A Buggl\MainBundle\Service\EGuideRequestEmailService instance.
     */
    protected function getBugglMain_EguideRequestServiceService()
    {
        return $this->services['buggl_main.eguide_request_service'] = new \Buggl\MainBundle\Service\EGuideRequestEmailService($this->get('mailer'), $this->get('templating'), $this->get('buggl_main.constants'), 'doctrine.orm.entity_manager', $this->get('router'));
    }

    /**
     * Gets the 'buggl_main.email_verification_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\EmailVerificationService A Buggl\MainBundle\Service\EmailVerificationService instance.
     */
    protected function getBugglMain_EmailVerificationServiceService()
    {
        return $this->services['buggl_main.email_verification_service'] = new \Buggl\MainBundle\Service\EmailVerificationService($this->get('mailer'), $this->get('templating'), $this->get('buggl_main.slugifier'), $this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.constants'), $this->get('router'));
    }

    /**
     * Gets the 'buggl_main.entity_repository' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\EntityRepositoryService A Buggl\MainBundle\Service\EntityRepositoryService instance.
     */
    protected function getBugglMain_EntityRepositoryService()
    {
        return $this->services['buggl_main.entity_repository'] = new \Buggl\MainBundle\Service\EntityRepositoryService($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.environment_variables' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\EnvironmentVariablesService A Buggl\MainBundle\Service\EnvironmentVariablesService instance.
     */
    protected function getBugglMain_EnvironmentVariablesService()
    {
        return $this->services['buggl_main.environment_variables'] = new \Buggl\MainBundle\Service\EnvironmentVariablesService();
    }

    /**
     * Gets the 'buggl_main.facebook_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\FacebookService A Buggl\MainBundle\Service\FacebookService instance.
     */
    protected function getBugglMain_FacebookServiceService()
    {
        return $this->services['buggl_main.facebook_service'] = new \Buggl\MainBundle\Service\FacebookService($this->get('buggl_main.environment_variables'), $this->get('doctrine.orm.default_entity_manager'), $this->get('router'));
    }

    /**
     * Gets the 'buggl_main.flickr_photo_search' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\FlickrPhotoSearchService A Buggl\MainBundle\Service\FlickrPhotoSearchService instance.
     */
    protected function getBugglMain_FlickrPhotoSearchService()
    {
        return $this->services['buggl_main.flickr_photo_search'] = new \Buggl\MainBundle\Service\FlickrPhotoSearchService();
    }

    /**
     * Gets the 'buggl_main.follow' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\FollowService A Buggl\MainBundle\Service\FollowService instance.
     */
    protected function getBugglMain_FollowService()
    {
        return $this->services['buggl_main.follow'] = new \Buggl\MainBundle\Service\FollowService($this->get('buggl_main.entity_repository'));
    }

    /**
     * Gets the 'buggl_main.free_search' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\EventListener\FreeSearchEventListener A Buggl\MainBundle\EventListener\FreeSearchEventListener instance.
     */
    protected function getBugglMain_FreeSearchService()
    {
        return $this->services['buggl_main.free_search'] = new \Buggl\MainBundle\EventListener\FreeSearchEventListener($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.free_search_raw_sql' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\FreeSearchQueryService A Buggl\MainBundle\Service\FreeSearchQueryService instance.
     */
    protected function getBugglMain_FreeSearchRawSqlService()
    {
        return $this->services['buggl_main.free_search_raw_sql'] = new \Buggl\MainBundle\Service\FreeSearchQueryService($this->get('doctrine.dbal.default_connection'));
    }

    /**
     * Gets the 'buggl_main.gmail_contacts_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\GmailContactsService A Buggl\MainBundle\Service\GmailContactsService instance.
     */
    protected function getBugglMain_GmailContactsServiceService()
    {
        return $this->services['buggl_main.gmail_contacts_service'] = new \Buggl\MainBundle\Service\GmailContactsService($this->get('buggl_main.environment_variables'));
    }

    /**
     * Gets the 'buggl_main.google_plus_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\GooglePlusService A Buggl\MainBundle\Service\GooglePlusService instance.
     */
    protected function getBugglMain_GooglePlusServiceService()
    {
        return $this->services['buggl_main.google_plus_service'] = new \Buggl\MainBundle\Service\GooglePlusService($this->get('buggl_main.environment_variables'), $this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.googleanalytics_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\GoogleAnalyticsService A Buggl\MainBundle\Service\GoogleAnalyticsService instance.
     */
    protected function getBugglMain_GoogleanalyticsServiceService()
    {
        return $this->services['buggl_main.googleanalytics_service'] = new \Buggl\MainBundle\Service\GoogleAnalyticsService($this->get('buggl_main.environment_variables'));
    }

    /**
     * Gets the 'buggl_main.guide_free_search' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\FreeSearchService A Buggl\MainBundle\Service\FreeSearchService instance.
     */
    protected function getBugglMain_GuideFreeSearchService()
    {
        return $this->services['buggl_main.guide_free_search'] = new \Buggl\MainBundle\Service\FreeSearchService($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.guide_search' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Helper\BugglSearchHelper A Buggl\MainBundle\Helper\BugglSearchHelper instance.
     */
    protected function getBugglMain_GuideSearchService()
    {
        return $this->services['buggl_main.guide_search'] = new \Buggl\MainBundle\Helper\BugglSearchHelper($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.guide_share_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\GuideShareService A Buggl\MainBundle\Service\GuideShareService instance.
     */
    protected function getBugglMain_GuideShareServiceService()
    {
        return $this->services['buggl_main.guide_share_service'] = new \Buggl\MainBundle\Service\GuideShareService($this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.constants'), $this->get('mailer'), $this->get('templating'), $this->get('router'));
    }

    /**
     * Gets the 'buggl_main.listener.activity' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\EventListener\ActivityListener A Buggl\MainBundle\EventListener\ActivityListener instance.
     */
    protected function getBugglMain_Listener_ActivityService()
    {
        return $this->services['buggl_main.listener.activity'] = new \Buggl\MainBundle\EventListener\ActivityListener($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.listener.eguide_category' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\EventListener\EguideListener A Buggl\MainBundle\EventListener\EguideListener instance.
     */
    protected function getBugglMain_Listener_EguideCategoryService()
    {
        return $this->services['buggl_main.listener.eguide_category'] = new \Buggl\MainBundle\EventListener\EguideListener($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.listener.follow' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\EventListener\FollowListener A Buggl\MainBundle\EventListener\FollowListener instance.
     */
    protected function getBugglMain_Listener_FollowService()
    {
        return $this->services['buggl_main.listener.follow'] = new \Buggl\MainBundle\EventListener\FollowListener($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.listener.local_reference' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\EventListener\LocalReferenceListener A Buggl\MainBundle\EventListener\LocalReferenceListener instance.
     */
    protected function getBugglMain_Listener_LocalReferenceService()
    {
        return $this->services['buggl_main.listener.local_reference'] = new \Buggl\MainBundle\EventListener\LocalReferenceListener($this->get('buggl_main.local_reference_service'));
    }

    /**
     * Gets the 'buggl_main.listener.password_reset' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\EventListener\PasswordResetListener A Buggl\MainBundle\EventListener\PasswordResetListener instance.
     */
    protected function getBugglMain_Listener_PasswordResetService()
    {
        return $this->services['buggl_main.listener.password_reset'] = new \Buggl\MainBundle\EventListener\PasswordResetListener($this->get('mailer'), $this->get('templating'), $this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.constants'), $this->get('router'));
    }

    /**
     * Gets the 'buggl_main.listener.registration' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\EventListener\RegistrationListener A Buggl\MainBundle\EventListener\RegistrationListener instance.
     */
    protected function getBugglMain_Listener_RegistrationService()
    {
        return $this->services['buggl_main.listener.registration'] = new \Buggl\MainBundle\EventListener\RegistrationListener($this->get('buggl_main.registration_service'));
    }

    /**
     * Gets the 'buggl_main.local_author_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\LocalAuthorService A Buggl\MainBundle\Service\LocalAuthorService instance.
     */
    protected function getBugglMain_LocalAuthorServiceService()
    {
        return $this->services['buggl_main.local_author_service'] = new \Buggl\MainBundle\Service\LocalAuthorService($this->get('doctrine.orm.default_entity_manager'), $this->get('mailer'), $this->get('templating'), $this->get('router'), $this->get('buggl_main.constants'));
    }

    /**
     * Gets the 'buggl_main.local_author_to_json' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\BugglLocalAuthorToArray A Buggl\MainBundle\Service\BugglLocalAuthorToArray instance.
     */
    protected function getBugglMain_LocalAuthorToJsonService()
    {
        return $this->services['buggl_main.local_author_to_json'] = new \Buggl\MainBundle\Service\BugglLocalAuthorToArray($this->get('router'), $this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.local_reference_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\LocalReferenceService A Buggl\MainBundle\Service\LocalReferenceService instance.
     */
    protected function getBugglMain_LocalReferenceServiceService()
    {
        return $this->services['buggl_main.local_reference_service'] = new \Buggl\MainBundle\Service\LocalReferenceService($this->get('mailer'), $this->get('templating'), $this->get('buggl_main.constants'), $this->get('doctrine.orm.default_entity_manager'), $this->get('router'));
    }

    /**
     * Gets the 'buggl_main.location' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\LocationService A Buggl\MainBundle\Service\LocationService instance.
     */
    protected function getBugglMain_LocationService()
    {
        return $this->services['buggl_main.location'] = new \Buggl\MainBundle\Service\LocationService($this->get('doctrine.orm.default_entity_manager'), $this->get('templating'));
    }

    /**
     * Gets the 'buggl_main.login_handler' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Handler\LoginHandler A Buggl\MainBundle\Handler\LoginHandler instance.
     */
    protected function getBugglMain_LoginHandlerService()
    {
        return $this->services['buggl_main.login_handler'] = new \Buggl\MainBundle\Handler\LoginHandler($this->get('router'), $this->get('security.context'));
    }

    /**
     * Gets the 'buggl_main.mail_message_builder' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\MessageBuilder A Buggl\MainBundle\Service\MessageBuilder instance.
     */
    protected function getBugglMain_MailMessageBuilderService()
    {
        return $this->services['buggl_main.mail_message_builder'] = new \Buggl\MainBundle\Service\MessageBuilder($this->get('templating'), $this->get('router'), $this->get('buggl_main.constants'));
    }

    /**
     * Gets the 'buggl_main.mail_notification' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\EventListener\MailNotificationListener A Buggl\MainBundle\EventListener\MailNotificationListener instance.
     */
    protected function getBugglMain_MailNotificationService()
    {
        return $this->services['buggl_main.mail_notification'] = new \Buggl\MainBundle\EventListener\MailNotificationListener($this->get('mailer'));
    }

    /**
     * Gets the 'buggl_main.message_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\MessageService A Buggl\MainBundle\Service\MessageService instance.
     */
    protected function getBugglMain_MessageServiceService()
    {
        return $this->services['buggl_main.message_service'] = new \Buggl\MainBundle\Service\MessageService($this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.constants'), $this->get('mailer'), $this->get('templating'), $this->get('router'));
    }

    /**
     * Gets the 'buggl_main.oauth2_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\OAuth2Service A Buggl\MainBundle\Service\OAuth2Service instance.
     */
    protected function getBugglMain_Oauth2ServiceService()
    {
        return $this->services['buggl_main.oauth2_service'] = new \Buggl\MainBundle\Service\OAuth2Service($this->get('buggl_main.environment_variables'));
    }

    /**
     * Gets the 'buggl_main.oauth_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\OAuthService A Buggl\MainBundle\Service\OAuthService instance.
     */
    protected function getBugglMain_OauthServiceService()
    {
        return $this->services['buggl_main.oauth_service'] = new \Buggl\MainBundle\Service\OAuthService();
    }

    /**
     * Gets the 'buggl_main.password_reset_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\PasswordResetService A Buggl\MainBundle\Service\PasswordResetService instance.
     */
    protected function getBugglMain_PasswordResetServiceService()
    {
        return $this->services['buggl_main.password_reset_service'] = new \Buggl\MainBundle\Service\PasswordResetService($this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.constants'), $this->get('buggl_main.local_author_service'));
    }

    /**
     * Gets the 'buggl_main.paypal_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\PaypalService A Buggl\MainBundle\Service\PaypalService instance.
     */
    protected function getBugglMain_PaypalServiceService()
    {
        return $this->services['buggl_main.paypal_service'] = new \Buggl\MainBundle\Service\PaypalService($this->get('buggl_main.environment_variables'), $this);
    }

    /**
     * Gets the 'buggl_main.pdf_creator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Helper\BugglPdfCreator A Buggl\MainBundle\Helper\BugglPdfCreator instance.
     */
    protected function getBugglMain_PdfCreatorService()
    {
        return $this->services['buggl_main.pdf_creator'] = new \Buggl\MainBundle\Helper\BugglPdfCreator($this->get('knp_snappy.pdf'), 'C:/xampp/htdocs/beta-buggl/app', $this->get('buggl_aws.wrapper'), $this->get('buggl_main.constants'));
    }

    /**
     * Gets the 'buggl_main.photo_cropper' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Helper\BugglPhotoCropper A Buggl\MainBundle\Helper\BugglPhotoCropper instance.
     */
    protected function getBugglMain_PhotoCropperService()
    {
        return $this->services['buggl_main.photo_cropper'] = new \Buggl\MainBundle\Helper\BugglPhotoCropper();
    }

    /**
     * Gets the 'buggl_main.photo_uploader' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\BugglPhotoUploaderService A Buggl\MainBundle\Service\BugglPhotoUploaderService instance.
     */
    protected function getBugglMain_PhotoUploaderService()
    {
        return $this->services['buggl_main.photo_uploader'] = new \Buggl\MainBundle\Service\BugglPhotoUploaderService($this->get('buggl_photo.uploader'));
    }

    /**
     * Gets the 'buggl_main.post_filter_last_active' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\EventListener\LastActiveFilterListener A Buggl\MainBundle\EventListener\LastActiveFilterListener instance.
     */
    protected function getBugglMain_PostFilterLastActiveService()
    {
        return $this->services['buggl_main.post_filter_last_active'] = new \Buggl\MainBundle\EventListener\LastActiveFilterListener($this->get('security.context'), $this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.profile_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\ProfileService A Buggl\MainBundle\Service\ProfileService instance.
     */
    protected function getBugglMain_ProfileServiceService()
    {
        return $this->services['buggl_main.profile_service'] = new \Buggl\MainBundle\Service\ProfileService($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.purchase_mailer_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\PurchaseMailerService A Buggl\MainBundle\Service\PurchaseMailerService instance.
     */
    protected function getBugglMain_PurchaseMailerServiceService()
    {
        return $this->services['buggl_main.purchase_mailer_service'] = new \Buggl\MainBundle\Service\PurchaseMailerService($this->get('buggl_main.mail_message_builder'), $this->get('event_dispatcher'));
    }

    /**
     * Gets the 'buggl_main.purchase_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\PurchaseService A Buggl\MainBundle\Service\PurchaseService instance.
     */
    protected function getBugglMain_PurchaseServiceService()
    {
        return $this->services['buggl_main.purchase_service'] = new \Buggl\MainBundle\Service\PurchaseService($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.rating' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\RatingService A Buggl\MainBundle\Service\RatingService instance.
     */
    protected function getBugglMain_RatingService()
    {
        return $this->services['buggl_main.rating'] = new \Buggl\MainBundle\Service\RatingService($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.registration_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\RegistrationService A Buggl\MainBundle\Service\RegistrationService instance.
     */
    protected function getBugglMain_RegistrationServiceService()
    {
        return $this->services['buggl_main.registration_service'] = new \Buggl\MainBundle\Service\RegistrationService($this->get('mailer'), $this->get('templating'), $this->get('buggl_main.slugifier'), $this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.constants'), $this->get('router'), $this->get('buggl_main.socialmedia'), $this);
    }

    /**
     * Gets the 'buggl_main.review' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\ReviewService A Buggl\MainBundle\Service\ReviewService instance.
     */
    protected function getBugglMain_ReviewService()
    {
        return $this->services['buggl_main.review'] = new \Buggl\MainBundle\Service\ReviewService($this->get('buggl_main.constants'), $this->get('doctrine.orm.default_entity_manager'), $this->get('security.context'));
    }

    /**
     * Gets the 'buggl_main.review_native_query' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\ReviewNativeQueryService A Buggl\MainBundle\Service\ReviewNativeQueryService instance.
     */
    protected function getBugglMain_ReviewNativeQueryService()
    {
        return $this->services['buggl_main.review_native_query'] = new \Buggl\MainBundle\Service\ReviewNativeQueryService($this->get('doctrine.dbal.default_connection'));
    }

    /**
     * Gets the 'buggl_main.search_local' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\BugglSearchLocalAuthor A Buggl\MainBundle\Service\BugglSearchLocalAuthor instance.
     */
    protected function getBugglMain_SearchLocalService()
    {
        return $this->services['buggl_main.search_local'] = new \Buggl\MainBundle\Service\BugglSearchLocalAuthor($this->get('buggl_main.entity_repository'));
    }

    /**
     * Gets the 'buggl_main.search_pictures' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\LocalAuthorPhotoService A Buggl\MainBundle\Service\LocalAuthorPhotoService instance.
     */
    protected function getBugglMain_SearchPicturesService()
    {
        return $this->services['buggl_main.search_pictures'] = new \Buggl\MainBundle\Service\LocalAuthorPhotoService($this->get('buggl_main.entity_repository'));
    }

    /**
     * Gets the 'buggl_main.share' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\ShareService A Buggl\MainBundle\Service\ShareService instance.
     */
    protected function getBugglMain_ShareService()
    {
        return $this->services['buggl_main.share'] = new \Buggl\MainBundle\Service\ShareService($this->get('mailer'), $this->get('templating'), $this->get('buggl_main.constants'), $this->get('doctrine.orm.default_entity_manager'), $this->get('router'));
    }

    /**
     * Gets the 'buggl_main.sitemap_builder' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Helper\BugglSitemapHelper A Buggl\MainBundle\Helper\BugglSitemapHelper instance.
     */
    protected function getBugglMain_SitemapBuilderService()
    {
        return $this->services['buggl_main.sitemap_builder'] = new \Buggl\MainBundle\Helper\BugglSitemapHelper($this->get('router'), 'C:/xampp/htdocs/beta-buggl/app', $this->get('doctrine.orm.default_entity_manager'), $this->get('buggl_main.slugifier'), $this->get('buggl_main.constants'));
    }

    /**
     * Gets the 'buggl_main.slugifier' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Helper\BugglSlugifier A Buggl\MainBundle\Helper\BugglSlugifier instance.
     */
    protected function getBugglMain_SlugifierService()
    {
        return $this->services['buggl_main.slugifier'] = new \Buggl\MainBundle\Helper\BugglSlugifier();
    }

    /**
     * Gets the 'buggl_main.socialmedia' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\SocialMediaService A Buggl\MainBundle\Service\SocialMediaService instance.
     */
    protected function getBugglMain_SocialmediaService()
    {
        return $this->services['buggl_main.socialmedia'] = new \Buggl\MainBundle\Service\SocialMediaService($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.stats_native_query' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\StatsNativeQuery A Buggl\MainBundle\Service\StatsNativeQuery instance.
     */
    protected function getBugglMain_StatsNativeQueryService()
    {
        return $this->services['buggl_main.stats_native_query'] = new \Buggl\MainBundle\Service\StatsNativeQuery($this->get('doctrine.dbal.default_connection'), $this->get('buggl_main.constants'));
    }

    /**
     * Gets the 'buggl_main.street_credit' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\StreetCreditService A Buggl\MainBundle\Service\StreetCreditService instance.
     */
    protected function getBugglMain_StreetCreditService()
    {
        return $this->services['buggl_main.street_credit'] = new \Buggl\MainBundle\Service\StreetCreditService($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.stripe_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\StripeService A Buggl\MainBundle\Service\StripeService instance.
     */
    protected function getBugglMain_StripeServiceService()
    {
        return $this->services['buggl_main.stripe_service'] = new \Buggl\MainBundle\Service\StripeService($this->get('buggl_main.environment_variables'), $this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.trip_theme' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\TripThemeService A Buggl\MainBundle\Service\TripThemeService instance.
     */
    protected function getBugglMain_TripThemeService()
    {
        return $this->services['buggl_main.trip_theme'] = new \Buggl\MainBundle\Service\TripThemeService($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.twitter_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\TwitterService A Buggl\MainBundle\Service\TwitterService instance.
     */
    protected function getBugglMain_TwitterServiceService()
    {
        return $this->services['buggl_main.twitter_service'] = new \Buggl\MainBundle\Service\TwitterService($this->get('buggl_main.environment_variables'), $this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_main.url_encoder' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Helper\BugglSearchUrlEncoder A Buggl\MainBundle\Helper\BugglSearchUrlEncoder instance.
     */
    protected function getBugglMain_UrlEncoderService()
    {
        return $this->services['buggl_main.url_encoder'] = new \Buggl\MainBundle\Helper\BugglSearchUrlEncoder();
    }

    /**
     * Gets the 'buggl_main.word_press_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\WordPressService A Buggl\MainBundle\Service\WordPressService instance.
     */
    protected function getBugglMain_WordPressServiceService()
    {
        require_once 'C:/xampp/htdocs/beta-buggl/app/../web/blog/wp-config.php';

        return $this->services['buggl_main.word_press_service'] = new \Buggl\MainBundle\Service\WordPressService();
    }

    /**
     * Gets the 'buggl_main.yahoomail_contacts_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\YahooMailContactsService A Buggl\MainBundle\Service\YahooMailContactsService instance.
     */
    protected function getBugglMain_YahoomailContactsServiceService()
    {
        return $this->services['buggl_main.yahoomail_contacts_service'] = new \Buggl\MainBundle\Service\YahooMailContactsService($this->get('buggl_main.environment_variables'));
    }

    /**
     * Gets the 'buggl_photo.uploader' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\PhotoBundle\Component\PhotoUploader A Buggl\PhotoBundle\Component\PhotoUploader instance.
     */
    protected function getBugglPhoto_UploaderService()
    {
        return $this->services['buggl_photo.uploader'] = new \Buggl\PhotoBundle\Component\PhotoUploader();
    }

    /**
     * Gets the 'buggl_photo.uploader_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\PhotoBundle\Service\PhotoUploaderService A Buggl\PhotoBundle\Service\PhotoUploaderService instance.
     */
    protected function getBugglPhoto_UploaderServiceService()
    {
        return $this->services['buggl_photo.uploader_service'] = new \Buggl\PhotoBundle\Service\PhotoUploaderService($this->get('buggl_photo.uploader'));
    }

    /**
     * Gets the 'buggl_seo.author_profile' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\SEO\LocalAuthorPageService A Buggl\MainBundle\Service\SEO\LocalAuthorPageService instance.
     */
    protected function getBugglSeo_AuthorProfileService()
    {
        return $this->services['buggl_seo.author_profile'] = new \Buggl\MainBundle\Service\SEO\LocalAuthorPageService($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_seo.full_guide' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\SEO\FullGuidePageService A Buggl\MainBundle\Service\SEO\FullGuidePageService instance.
     */
    protected function getBugglSeo_FullGuideService()
    {
        return $this->services['buggl_seo.full_guide'] = new \Buggl\MainBundle\Service\SEO\FullGuidePageService($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_seo.full_spot' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\SEO\FullSpotPageService A Buggl\MainBundle\Service\SEO\FullSpotPageService instance.
     */
    protected function getBugglSeo_FullSpotService()
    {
        return $this->services['buggl_seo.full_spot'] = new \Buggl\MainBundle\Service\SEO\FullSpotPageService($this->get('doctrine.orm.default_entity_manager'));
    }

    /**
     * Gets the 'buggl_seo.search_results' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\SEO\SearchResultsPageService A Buggl\MainBundle\Service\SEO\SearchResultsPageService instance.
     */
    protected function getBugglSeo_SearchResultsService()
    {
        return $this->services['buggl_seo.search_results'] = new \Buggl\MainBundle\Service\SEO\SearchResultsPageService();
    }

    /**
     * Gets the 'buggl_seo.static_page' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Buggl\MainBundle\Service\SEO\StaticPageService A Buggl\MainBundle\Service\SEO\StaticPageService instance.
     */
    protected function getBugglSeo_StaticPageService()
    {
        return $this->services['buggl_seo.static_page'] = new \Buggl\MainBundle\Service\SEO\StaticPageService();
    }

    /**
     * Gets the 'cache_clearer' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpKernel\CacheClearer\ChainCacheClearer A Symfony\Component\HttpKernel\CacheClearer\ChainCacheClearer instance.
     */
    protected function getCacheClearerService()
    {
        return $this->services['cache_clearer'] = new \Symfony\Component\HttpKernel\CacheClearer\ChainCacheClearer(array());
    }

    /**
     * Gets the 'cache_warmer' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerAggregate A Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerAggregate instance.
     */
    protected function getCacheWarmerService()
    {
        $a = $this->get('kernel');
        $b = $this->get('templating.filename_parser');

        $c = new \Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplateFinder($a, $b, 'C:/xampp/htdocs/beta-buggl/app/Resources');

        return $this->services['cache_warmer'] = new \Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerAggregate(array(0 => new \Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplatePathsCacheWarmer($c, $this->get('templating.locator')), 1 => new \Symfony\Bundle\AsseticBundle\CacheWarmer\AssetManagerCacheWarmer($this), 2 => new \Symfony\Bundle\FrameworkBundle\CacheWarmer\RouterCacheWarmer($this->get('router')), 3 => new \Symfony\Bundle\TwigBundle\CacheWarmer\TemplateCacheCacheWarmer($this, $c), 4 => new \Symfony\Bridge\Doctrine\CacheWarmer\ProxyCacheWarmer($this->get('doctrine')), 5 => new \JMS\DiExtraBundle\HttpKernel\ControllerInjectorsWarmer($a, $this->get('debug.controller_resolver'), array())));
    }

    /**
     * Gets the 'debug.controller_resolver' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return JMS\DiExtraBundle\HttpKernel\ControllerResolver A JMS\DiExtraBundle\HttpKernel\ControllerResolver instance.
     */
    protected function getDebug_ControllerResolverService()
    {
        return $this->services['debug.controller_resolver'] = new \JMS\DiExtraBundle\HttpKernel\ControllerResolver($this, $this->get('controller_name_converter'), $this->get('monolog.logger.request'));
    }

    /**
     * Gets the 'debug.stopwatch' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpKernel\Debug\Stopwatch A Symfony\Component\HttpKernel\Debug\Stopwatch instance.
     */
    protected function getDebug_StopwatchService()
    {
        return $this->services['debug.stopwatch'] = new \Symfony\Component\HttpKernel\Debug\Stopwatch();
    }

    /**
     * Gets the 'doctrine' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Doctrine\Bundle\DoctrineBundle\Registry A Doctrine\Bundle\DoctrineBundle\Registry instance.
     */
    protected function getDoctrineService()
    {
        return $this->services['doctrine'] = new \Doctrine\Bundle\DoctrineBundle\Registry($this, array('default' => 'doctrine.dbal.default_connection'), array('default' => 'doctrine.orm.default_entity_manager'), 'default', 'default');
    }

    /**
     * Gets the 'doctrine.dbal.connection_factory' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Doctrine\Bundle\DoctrineBundle\ConnectionFactory A Doctrine\Bundle\DoctrineBundle\ConnectionFactory instance.
     */
    protected function getDoctrine_Dbal_ConnectionFactoryService()
    {
        return $this->services['doctrine.dbal.connection_factory'] = new \Doctrine\Bundle\DoctrineBundle\ConnectionFactory(array());
    }

    /**
     * Gets the 'doctrine.dbal.default_connection' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return stdClass A stdClass instance.
     */
    protected function getDoctrine_Dbal_DefaultConnectionService()
    {
        $a = new \Doctrine\DBAL\Logging\LoggerChain();
        $a->addLogger(new \Symfony\Bridge\Doctrine\Logger\DbalLogger($this->get('monolog.logger.doctrine'), $this->get('debug.stopwatch')));
        $a->addLogger(new \Doctrine\DBAL\Logging\DebugStack());

        $b = new \Doctrine\DBAL\Configuration();
        $b->setSQLLogger($a);

        return $this->services['doctrine.dbal.default_connection'] = $this->get('doctrine.dbal.connection_factory')->createConnection(array('dbname' => 'buggl_beta_may31', 'host' => 'localhost', 'port' => NULL, 'user' => 'buggl', 'password' => 'FRJbXdmmHCUW5EjM', 'charset' => 'UTF8', 'driver' => 'pdo_mysql', 'driverOptions' => array()), $b, new \Symfony\Bridge\Doctrine\ContainerAwareEventManager($this), array());
    }

    /**
     * Gets the 'doctrine.orm.default_entity_manager' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return EntityManager566ea66f2289e_546a8d27f194334ee012bfe64f629947b07e4919\__CG__\Doctrine\ORM\EntityManager A EntityManager566ea66f2289e_546a8d27f194334ee012bfe64f629947b07e4919\__CG__\Doctrine\ORM\EntityManager instance.
     */
    protected function getDoctrine_Orm_DefaultEntityManagerService()
    {
        require_once 'C:/xampp/htdocs/beta-buggl/app/cache/prod/jms_diextra/doctrine/EntityManager_566ea66f2289e.php';

        $a = $this->get('annotation_reader');

        $b = new \Doctrine\Common\Cache\ArrayCache();
        $b->setNamespace('sf2orm_default_536b9164ac533ab5dda3ac741f028da2');

        $c = new \Doctrine\Common\Cache\ArrayCache();
        $c->setNamespace('sf2orm_default_536b9164ac533ab5dda3ac741f028da2');

        $d = new \Doctrine\Common\Cache\ArrayCache();
        $d->setNamespace('sf2orm_default_536b9164ac533ab5dda3ac741f028da2');

        $e = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($a, array(0 => 'C:\\xampp\\htdocs\\beta-buggl\\src\\Buggl\\MainBundle\\Entity', 1 => 'C:\\xampp\\htdocs\\beta-buggl\\src\\Buggl\\PhotoBundle\\Entity'));

        $f = new \Doctrine\ORM\Mapping\Driver\DriverChain();
        $f->addDriver($e, 'Buggl\\MainBundle\\Entity');
        $f->addDriver($e, 'Buggl\\PhotoBundle\\Entity');

        $g = new \Doctrine\ORM\Configuration();
        $g->setEntityNamespaces(array('BugglMainBundle' => 'Buggl\\MainBundle\\Entity', 'BugglPhotoBundle' => 'Buggl\\PhotoBundle\\Entity'));
        $g->setMetadataCacheImpl($b);
        $g->setQueryCacheImpl($c);
        $g->setResultCacheImpl($d);
        $g->setMetadataDriverImpl($f);
        $g->setProxyDir('C:/xampp/htdocs/beta-buggl/app/cache/prod/doctrine/orm/Proxies');
        $g->setProxyNamespace('Proxies');
        $g->setAutoGenerateProxyClasses(true);
        $g->setClassMetadataFactoryName('Doctrine\\ORM\\Mapping\\ClassMetadataFactory');
        $g->setDefaultRepositoryClassName('Doctrine\\ORM\\EntityRepository');
        $g->setNamingStrategy(new \Doctrine\ORM\Mapping\DefaultNamingStrategy());

        $h = call_user_func(array('Doctrine\\ORM\\EntityManager', 'create'), $this->get('doctrine.dbal.default_connection'), $g);
        $this->get('doctrine.orm.default_manager_configurator')->configure($h);

        return $this->services['doctrine.orm.default_entity_manager'] = new \EntityManager566ea66f2289e_546a8d27f194334ee012bfe64f629947b07e4919\__CG__\Doctrine\ORM\EntityManager($h, $this);
    }

    /**
     * Gets the 'doctrine.orm.default_manager_configurator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Doctrine\Bundle\DoctrineBundle\ManagerConfigurator A Doctrine\Bundle\DoctrineBundle\ManagerConfigurator instance.
     */
    protected function getDoctrine_Orm_DefaultManagerConfiguratorService()
    {
        return $this->services['doctrine.orm.default_manager_configurator'] = new \Doctrine\Bundle\DoctrineBundle\ManagerConfigurator(array());
    }

    /**
     * Gets the 'doctrine.orm.validator.unique' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator A Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator instance.
     */
    protected function getDoctrine_Orm_Validator_UniqueService()
    {
        return $this->services['doctrine.orm.validator.unique'] = new \Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator($this->get('doctrine'));
    }

    /**
     * Gets the 'doctrine.orm.validator_initializer' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bridge\Doctrine\Validator\DoctrineInitializer A Symfony\Bridge\Doctrine\Validator\DoctrineInitializer instance.
     */
    protected function getDoctrine_Orm_ValidatorInitializerService()
    {
        return $this->services['doctrine.orm.validator_initializer'] = new \Symfony\Bridge\Doctrine\Validator\DoctrineInitializer($this->get('doctrine'));
    }

    /**
     * Gets the 'event_dispatcher' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher A Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher instance.
     */
    protected function getEventDispatcherService()
    {
        $this->services['event_dispatcher'] = $instance = new \Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher($this, $this->get('debug.stopwatch'), $this->get('monolog.logger.event'));

        $instance->addListenerService('kernel.exception', array(0 => 'buggl_main.custom_exception', 1 => 'onKernelException'), 0);
        $instance->addListenerService('kernel.request', array(0 => 'security.firewall', 1 => 'onKernelRequest'), 8);
        $instance->addListenerService('kernel.response', array(0 => 'security.rememberme.response_listener', 1 => 'onKernelResponse'), 0);
        $instance->addListenerService('kernel.request', array(0 => 'assetic.request_listener', 1 => 'onKernelRequest'), 0);
        $instance->addListenerService('kernel.controller', array(0 => 'sensio_framework_extra.controller.listener', 1 => 'onKernelController'), 0);
        $instance->addListenerService('kernel.controller', array(0 => 'sensio_framework_extra.converter.listener', 1 => 'onKernelController'), 0);
        $instance->addListenerService('kernel.controller', array(0 => 'sensio_framework_extra.view.listener', 1 => 'onKernelController'), 0);
        $instance->addListenerService('kernel.view', array(0 => 'sensio_framework_extra.view.listener', 1 => 'onKernelView'), 0);
        $instance->addListenerService('kernel.response', array(0 => 'sensio_framework_extra.cache.listener', 1 => 'onKernelResponse'), 0);
        $instance->addListenerService('buggl.local_author_registration', array(0 => 'buggl_main.listener.registration', 1 => 'mailPostRegister'), 0);
        $instance->addListenerService('buggl.local_author_registration', array(0 => 'buggl_main.listener.registration', 1 => 'update'), 0);
        $instance->addListenerService('buggl.verify_email', array(0 => 'buggl_main.listener.registration', 1 => 'sendMail'), 0);
        $instance->addListenerService('buggl.activity', array(0 => 'buggl_main.listener.activity', 1 => 'handleActivity'), 0);
        $instance->addListenerService('buggl.password_reset_request', array(0 => 'buggl_main.listener.password_reset', 1 => 'sendPasswordResetEmail'), 0);
        $instance->addListenerService('buggl.eguide_update_category', array(0 => 'buggl_main.listener.eguide_category', 1 => 'updateEguideCategoryNames'), 0);
        $instance->addListenerService('buggl.local_reference_response', array(0 => 'buggl_main.listener.local_reference', 1 => 'updateLocalReferenceRequestStatus'), 0);
        $instance->addListenerService('kernel.controller', array(0 => 'buggl.authenticate.action_listener', 1 => 'onKernelController'), 0);
        $instance->addListenerService('buggl.follow_user', array(0 => 'buggl_main.listener.follow', 1 => 'update'), 0);
        $instance->addListenerService('buggl.republish_eguide', array(0 => 'buggl_main.mail_notification', 1 => 'sendMail'), 0);
        $instance->addListenerService('buggl.denied_eguide', array(0 => 'buggl_main.mail_notification', 1 => 'sendMail'), 0);
        $instance->addListenerService('buggl.approve_eguide', array(0 => 'buggl_main.mail_notification', 1 => 'sendMail'), 0);
        $instance->addListenerService('buggl.contact_us', array(0 => 'buggl_main.mail_notification', 1 => 'sendMail'), 0);
        $instance->addListenerService('buggl.review', array(0 => 'buggl_main.mail_notification', 1 => 'sendMail'), 0);
        $instance->addListenerService('buggl.purchase', array(0 => 'buggl_main.mail_notification', 1 => 'sendMail'), 0);
        $instance->addListenerService('buggl.notify_buyer', array(0 => 'buggl_main.mail_notification', 1 => 'sendMail'), 0);
        $instance->addListenerService('buggl.account_suspended', array(0 => 'buggl_main.mail_notification', 1 => 'sendMail'), 0);
        $instance->addListenerService('kernel.request', array(0 => 'buggl_main.post_filter_last_active', 1 => 'onKernelRequest'), 0);
        $instance->addListenerService('buggl.update_free_search', array(0 => 'buggl_main.free_search', 1 => 'updateFreeSearchField'), 0);
        $instance->addSubscriberService('response_listener', 'Symfony\\Component\\HttpKernel\\EventListener\\ResponseListener');
        $instance->addSubscriberService('streamed_response_listener', 'Symfony\\Component\\HttpKernel\\EventListener\\StreamedResponseListener');
        $instance->addSubscriberService('locale_listener', 'Symfony\\Component\\HttpKernel\\EventListener\\LocaleListener');
        $instance->addSubscriberService('session_listener', 'Symfony\\Bundle\\FrameworkBundle\\EventListener\\SessionListener');
        $instance->addSubscriberService('router_listener', 'Symfony\\Component\\HttpKernel\\EventListener\\RouterListener');
        $instance->addSubscriberService('twig.exception_listener', 'Symfony\\Component\\HttpKernel\\EventListener\\ExceptionListener');
        $instance->addSubscriberService('swiftmailer.email_sender.listener', 'Symfony\\Bundle\\SwiftmailerBundle\\EventListener\\EmailSenderListener');

        return $instance;
    }

    /**
     * Gets the 'file_locator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpKernel\Config\FileLocator A Symfony\Component\HttpKernel\Config\FileLocator instance.
     */
    protected function getFileLocatorService()
    {
        return $this->services['file_locator'] = new \Symfony\Component\HttpKernel\Config\FileLocator($this->get('kernel'), 'C:/xampp/htdocs/beta-buggl/app/Resources');
    }

    /**
     * Gets the 'filesystem' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Filesystem\Filesystem A Symfony\Component\Filesystem\Filesystem instance.
     */
    protected function getFilesystemService()
    {
        return $this->services['filesystem'] = new \Symfony\Component\Filesystem\Filesystem();
    }

    /**
     * Gets the 'form.csrf_provider' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider A Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider instance.
     */
    protected function getForm_CsrfProviderService()
    {
        return $this->services['form.csrf_provider'] = new \Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider($this->get('session'), 'ThisTokenIsNotSoSecretChangeIt');
    }

    /**
     * Gets the 'form.factory' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\FormFactory A Symfony\Component\Form\FormFactory instance.
     */
    protected function getForm_FactoryService()
    {
        return $this->services['form.factory'] = new \Symfony\Component\Form\FormFactory($this->get('form.registry'), $this->get('form.resolved_type_factory'));
    }

    /**
     * Gets the 'form.registry' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\FormRegistry A Symfony\Component\Form\FormRegistry instance.
     */
    protected function getForm_RegistryService()
    {
        return $this->services['form.registry'] = new \Symfony\Component\Form\FormRegistry(array(0 => new \Symfony\Component\Form\Extension\DependencyInjection\DependencyInjectionExtension($this, array('field' => 'form.type.field', 'form' => 'form.type.form', 'birthday' => 'form.type.birthday', 'checkbox' => 'form.type.checkbox', 'choice' => 'form.type.choice', 'collection' => 'form.type.collection', 'country' => 'form.type.country', 'date' => 'form.type.date', 'datetime' => 'form.type.datetime', 'email' => 'form.type.email', 'file' => 'form.type.file', 'hidden' => 'form.type.hidden', 'integer' => 'form.type.integer', 'language' => 'form.type.language', 'locale' => 'form.type.locale', 'money' => 'form.type.money', 'number' => 'form.type.number', 'password' => 'form.type.password', 'percent' => 'form.type.percent', 'radio' => 'form.type.radio', 'repeated' => 'form.type.repeated', 'search' => 'form.type.search', 'textarea' => 'form.type.textarea', 'text' => 'form.type.text', 'time' => 'form.type.time', 'timezone' => 'form.type.timezone', 'url' => 'form.type.url', 'entity' => 'form.type.entity'), array('form' => array(0 => 'form.type_extension.form.http_foundation', 1 => 'form.type_extension.form.validator', 2 => 'form.type_extension.csrf'), 'repeated' => array(0 => 'form.type_extension.repeated.validator')), array(0 => 'form.type_guesser.validator', 1 => 'form.type_guesser.doctrine'))), $this->get('form.resolved_type_factory'));
    }

    /**
     * Gets the 'form.resolved_type_factory' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\ResolvedFormTypeFactory A Symfony\Component\Form\ResolvedFormTypeFactory instance.
     */
    protected function getForm_ResolvedTypeFactoryService()
    {
        return $this->services['form.resolved_type_factory'] = new \Symfony\Component\Form\ResolvedFormTypeFactory();
    }

    /**
     * Gets the 'form.type.birthday' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\BirthdayType A Symfony\Component\Form\Extension\Core\Type\BirthdayType instance.
     */
    protected function getForm_Type_BirthdayService()
    {
        return $this->services['form.type.birthday'] = new \Symfony\Component\Form\Extension\Core\Type\BirthdayType();
    }

    /**
     * Gets the 'form.type.checkbox' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\CheckboxType A Symfony\Component\Form\Extension\Core\Type\CheckboxType instance.
     */
    protected function getForm_Type_CheckboxService()
    {
        return $this->services['form.type.checkbox'] = new \Symfony\Component\Form\Extension\Core\Type\CheckboxType();
    }

    /**
     * Gets the 'form.type.choice' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\ChoiceType A Symfony\Component\Form\Extension\Core\Type\ChoiceType instance.
     */
    protected function getForm_Type_ChoiceService()
    {
        return $this->services['form.type.choice'] = new \Symfony\Component\Form\Extension\Core\Type\ChoiceType();
    }

    /**
     * Gets the 'form.type.collection' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\CollectionType A Symfony\Component\Form\Extension\Core\Type\CollectionType instance.
     */
    protected function getForm_Type_CollectionService()
    {
        return $this->services['form.type.collection'] = new \Symfony\Component\Form\Extension\Core\Type\CollectionType();
    }

    /**
     * Gets the 'form.type.country' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\CountryType A Symfony\Component\Form\Extension\Core\Type\CountryType instance.
     */
    protected function getForm_Type_CountryService()
    {
        return $this->services['form.type.country'] = new \Symfony\Component\Form\Extension\Core\Type\CountryType();
    }

    /**
     * Gets the 'form.type.date' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\DateType A Symfony\Component\Form\Extension\Core\Type\DateType instance.
     */
    protected function getForm_Type_DateService()
    {
        return $this->services['form.type.date'] = new \Symfony\Component\Form\Extension\Core\Type\DateType();
    }

    /**
     * Gets the 'form.type.datetime' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\DateTimeType A Symfony\Component\Form\Extension\Core\Type\DateTimeType instance.
     */
    protected function getForm_Type_DatetimeService()
    {
        return $this->services['form.type.datetime'] = new \Symfony\Component\Form\Extension\Core\Type\DateTimeType();
    }

    /**
     * Gets the 'form.type.email' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\EmailType A Symfony\Component\Form\Extension\Core\Type\EmailType instance.
     */
    protected function getForm_Type_EmailService()
    {
        return $this->services['form.type.email'] = new \Symfony\Component\Form\Extension\Core\Type\EmailType();
    }

    /**
     * Gets the 'form.type.entity' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bridge\Doctrine\Form\Type\EntityType A Symfony\Bridge\Doctrine\Form\Type\EntityType instance.
     */
    protected function getForm_Type_EntityService()
    {
        return $this->services['form.type.entity'] = new \Symfony\Bridge\Doctrine\Form\Type\EntityType($this->get('doctrine'));
    }

    /**
     * Gets the 'form.type.field' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\FieldType A Symfony\Component\Form\Extension\Core\Type\FieldType instance.
     */
    protected function getForm_Type_FieldService()
    {
        return $this->services['form.type.field'] = new \Symfony\Component\Form\Extension\Core\Type\FieldType();
    }

    /**
     * Gets the 'form.type.file' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\FileType A Symfony\Component\Form\Extension\Core\Type\FileType instance.
     */
    protected function getForm_Type_FileService()
    {
        return $this->services['form.type.file'] = new \Symfony\Component\Form\Extension\Core\Type\FileType();
    }

    /**
     * Gets the 'form.type.form' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\FormType A Symfony\Component\Form\Extension\Core\Type\FormType instance.
     */
    protected function getForm_Type_FormService()
    {
        return $this->services['form.type.form'] = new \Symfony\Component\Form\Extension\Core\Type\FormType();
    }

    /**
     * Gets the 'form.type.hidden' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\HiddenType A Symfony\Component\Form\Extension\Core\Type\HiddenType instance.
     */
    protected function getForm_Type_HiddenService()
    {
        return $this->services['form.type.hidden'] = new \Symfony\Component\Form\Extension\Core\Type\HiddenType();
    }

    /**
     * Gets the 'form.type.integer' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\IntegerType A Symfony\Component\Form\Extension\Core\Type\IntegerType instance.
     */
    protected function getForm_Type_IntegerService()
    {
        return $this->services['form.type.integer'] = new \Symfony\Component\Form\Extension\Core\Type\IntegerType();
    }

    /**
     * Gets the 'form.type.language' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\LanguageType A Symfony\Component\Form\Extension\Core\Type\LanguageType instance.
     */
    protected function getForm_Type_LanguageService()
    {
        return $this->services['form.type.language'] = new \Symfony\Component\Form\Extension\Core\Type\LanguageType();
    }

    /**
     * Gets the 'form.type.locale' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\LocaleType A Symfony\Component\Form\Extension\Core\Type\LocaleType instance.
     */
    protected function getForm_Type_LocaleService()
    {
        return $this->services['form.type.locale'] = new \Symfony\Component\Form\Extension\Core\Type\LocaleType();
    }

    /**
     * Gets the 'form.type.money' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\MoneyType A Symfony\Component\Form\Extension\Core\Type\MoneyType instance.
     */
    protected function getForm_Type_MoneyService()
    {
        return $this->services['form.type.money'] = new \Symfony\Component\Form\Extension\Core\Type\MoneyType();
    }

    /**
     * Gets the 'form.type.number' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\NumberType A Symfony\Component\Form\Extension\Core\Type\NumberType instance.
     */
    protected function getForm_Type_NumberService()
    {
        return $this->services['form.type.number'] = new \Symfony\Component\Form\Extension\Core\Type\NumberType();
    }

    /**
     * Gets the 'form.type.password' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\PasswordType A Symfony\Component\Form\Extension\Core\Type\PasswordType instance.
     */
    protected function getForm_Type_PasswordService()
    {
        return $this->services['form.type.password'] = new \Symfony\Component\Form\Extension\Core\Type\PasswordType();
    }

    /**
     * Gets the 'form.type.percent' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\PercentType A Symfony\Component\Form\Extension\Core\Type\PercentType instance.
     */
    protected function getForm_Type_PercentService()
    {
        return $this->services['form.type.percent'] = new \Symfony\Component\Form\Extension\Core\Type\PercentType();
    }

    /**
     * Gets the 'form.type.radio' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\RadioType A Symfony\Component\Form\Extension\Core\Type\RadioType instance.
     */
    protected function getForm_Type_RadioService()
    {
        return $this->services['form.type.radio'] = new \Symfony\Component\Form\Extension\Core\Type\RadioType();
    }

    /**
     * Gets the 'form.type.repeated' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\RepeatedType A Symfony\Component\Form\Extension\Core\Type\RepeatedType instance.
     */
    protected function getForm_Type_RepeatedService()
    {
        return $this->services['form.type.repeated'] = new \Symfony\Component\Form\Extension\Core\Type\RepeatedType();
    }

    /**
     * Gets the 'form.type.search' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\SearchType A Symfony\Component\Form\Extension\Core\Type\SearchType instance.
     */
    protected function getForm_Type_SearchService()
    {
        return $this->services['form.type.search'] = new \Symfony\Component\Form\Extension\Core\Type\SearchType();
    }

    /**
     * Gets the 'form.type.text' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\TextType A Symfony\Component\Form\Extension\Core\Type\TextType instance.
     */
    protected function getForm_Type_TextService()
    {
        return $this->services['form.type.text'] = new \Symfony\Component\Form\Extension\Core\Type\TextType();
    }

    /**
     * Gets the 'form.type.textarea' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\TextareaType A Symfony\Component\Form\Extension\Core\Type\TextareaType instance.
     */
    protected function getForm_Type_TextareaService()
    {
        return $this->services['form.type.textarea'] = new \Symfony\Component\Form\Extension\Core\Type\TextareaType();
    }

    /**
     * Gets the 'form.type.time' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\TimeType A Symfony\Component\Form\Extension\Core\Type\TimeType instance.
     */
    protected function getForm_Type_TimeService()
    {
        return $this->services['form.type.time'] = new \Symfony\Component\Form\Extension\Core\Type\TimeType();
    }

    /**
     * Gets the 'form.type.timezone' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\TimezoneType A Symfony\Component\Form\Extension\Core\Type\TimezoneType instance.
     */
    protected function getForm_Type_TimezoneService()
    {
        return $this->services['form.type.timezone'] = new \Symfony\Component\Form\Extension\Core\Type\TimezoneType();
    }

    /**
     * Gets the 'form.type.url' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Core\Type\UrlType A Symfony\Component\Form\Extension\Core\Type\UrlType instance.
     */
    protected function getForm_Type_UrlService()
    {
        return $this->services['form.type.url'] = new \Symfony\Component\Form\Extension\Core\Type\UrlType();
    }

    /**
     * Gets the 'form.type_extension.csrf' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Csrf\Type\FormTypeCsrfExtension A Symfony\Component\Form\Extension\Csrf\Type\FormTypeCsrfExtension instance.
     */
    protected function getForm_TypeExtension_CsrfService()
    {
        return $this->services['form.type_extension.csrf'] = new \Symfony\Component\Form\Extension\Csrf\Type\FormTypeCsrfExtension($this->get('form.csrf_provider'), true, '_token');
    }

    /**
     * Gets the 'form.type_extension.form.http_foundation' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\HttpFoundation\Type\FormTypeHttpFoundationExtension A Symfony\Component\Form\Extension\HttpFoundation\Type\FormTypeHttpFoundationExtension instance.
     */
    protected function getForm_TypeExtension_Form_HttpFoundationService()
    {
        return $this->services['form.type_extension.form.http_foundation'] = new \Symfony\Component\Form\Extension\HttpFoundation\Type\FormTypeHttpFoundationExtension();
    }

    /**
     * Gets the 'form.type_extension.form.validator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension A Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension instance.
     */
    protected function getForm_TypeExtension_Form_ValidatorService()
    {
        return $this->services['form.type_extension.form.validator'] = new \Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension($this->get('validator'));
    }

    /**
     * Gets the 'form.type_extension.repeated.validator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Validator\Type\RepeatedTypeValidatorExtension A Symfony\Component\Form\Extension\Validator\Type\RepeatedTypeValidatorExtension instance.
     */
    protected function getForm_TypeExtension_Repeated_ValidatorService()
    {
        return $this->services['form.type_extension.repeated.validator'] = new \Symfony\Component\Form\Extension\Validator\Type\RepeatedTypeValidatorExtension();
    }

    /**
     * Gets the 'form.type_guesser.doctrine' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bridge\Doctrine\Form\DoctrineOrmTypeGuesser A Symfony\Bridge\Doctrine\Form\DoctrineOrmTypeGuesser instance.
     */
    protected function getForm_TypeGuesser_DoctrineService()
    {
        return $this->services['form.type_guesser.doctrine'] = new \Symfony\Bridge\Doctrine\Form\DoctrineOrmTypeGuesser($this->get('doctrine'));
    }

    /**
     * Gets the 'form.type_guesser.validator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser A Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser instance.
     */
    protected function getForm_TypeGuesser_ValidatorService()
    {
        return $this->services['form.type_guesser.validator'] = new \Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser($this->get('validator.mapping.class_metadata_factory'));
    }

    /**
     * Gets the 'http_kernel' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\HttpKernel A Symfony\Bundle\FrameworkBundle\HttpKernel instance.
     */
    protected function getHttpKernelService()
    {
        return $this->services['http_kernel'] = new \Symfony\Bundle\FrameworkBundle\HttpKernel($this->get('event_dispatcher'), $this, $this->get('debug.controller_resolver'));
    }

    /**
     * Gets the 'jms_aop.interceptor_loader' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return JMS\AopBundle\Aop\InterceptorLoader A JMS\AopBundle\Aop\InterceptorLoader instance.
     */
    protected function getJmsAop_InterceptorLoaderService()
    {
        return $this->services['jms_aop.interceptor_loader'] = new \JMS\AopBundle\Aop\InterceptorLoader($this, array());
    }

    /**
     * Gets the 'jms_aop.pointcut_container' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return JMS\AopBundle\Aop\PointcutContainer A JMS\AopBundle\Aop\PointcutContainer instance.
     */
    protected function getJmsAop_PointcutContainerService()
    {
        return $this->services['jms_aop.pointcut_container'] = new \JMS\AopBundle\Aop\PointcutContainer(array('security.access.method_interceptor' => $this->get('security.access.pointcut')));
    }

    /**
     * Gets the 'jms_di_extra.metadata.converter' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return JMS\DiExtraBundle\Metadata\MetadataConverter A JMS\DiExtraBundle\Metadata\MetadataConverter instance.
     */
    protected function getJmsDiExtra_Metadata_ConverterService()
    {
        return $this->services['jms_di_extra.metadata.converter'] = new \JMS\DiExtraBundle\Metadata\MetadataConverter();
    }

    /**
     * Gets the 'jms_di_extra.metadata.metadata_factory' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Metadata\MetadataFactory A Metadata\MetadataFactory instance.
     */
    protected function getJmsDiExtra_Metadata_MetadataFactoryService()
    {
        $this->services['jms_di_extra.metadata.metadata_factory'] = $instance = new \Metadata\MetadataFactory(new \Metadata\Driver\LazyLoadingDriver($this, 'jms_di_extra.metadata_driver'), 'Metadata\\ClassHierarchyMetadata', true);

        $instance->setCache(new \Metadata\Cache\FileCache('C:/xampp/htdocs/beta-buggl/app/cache/prod/jms_diextra/metadata'));

        return $instance;
    }

    /**
     * Gets the 'jms_di_extra.metadata_driver' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return JMS\DiExtraBundle\Metadata\Driver\AnnotationDriver A JMS\DiExtraBundle\Metadata\Driver\AnnotationDriver instance.
     */
    protected function getJmsDiExtra_MetadataDriverService()
    {
        return $this->services['jms_di_extra.metadata_driver'] = new \JMS\DiExtraBundle\Metadata\Driver\AnnotationDriver($this->get('annotation_reader'));
    }

    /**
     * Gets the 'kernel' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getKernelService()
    {
        throw new RuntimeException('You have requested a synthetic service ("kernel"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the 'knp_snappy.image' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator A Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator instance.
     */
    protected function getKnpSnappy_ImageService()
    {
        return $this->services['knp_snappy.image'] = new \Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator(new \Knp\Snappy\Image('wkhtmltoimage', array(), array()), $this->get('monolog.logger.snappy'));
    }

    /**
     * Gets the 'knp_snappy.pdf' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator A Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator instance.
     */
    protected function getKnpSnappy_PdfService()
    {
        return $this->services['knp_snappy.pdf'] = new \Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator(new \Knp\Snappy\Pdf('/media/ebs1/websites/beta.buggl.com/wkhtmltopdf', array(), array()), $this->get('monolog.logger.snappy'));
    }

    /**
     * Gets the 'locale_listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpKernel\EventListener\LocaleListener A Symfony\Component\HttpKernel\EventListener\LocaleListener instance.
     */
    protected function getLocaleListenerService()
    {
        return $this->services['locale_listener'] = new \Symfony\Component\HttpKernel\EventListener\LocaleListener('en', $this->get('router'));
    }

    /**
     * Gets the 'logger' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bridge\Monolog\Logger A Symfony\Bridge\Monolog\Logger instance.
     */
    protected function getLoggerService()
    {
        $this->services['logger'] = $instance = new \Symfony\Bridge\Monolog\Logger('app');

        $instance->pushHandler($this->get('monolog.handler.main'));

        return $instance;
    }

    /**
     * Gets the 'mailer' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Swift_Mailer A Swift_Mailer instance.
     */
    protected function getMailerService()
    {
        return $this->services['mailer'] = new \Swift_Mailer($this->get('swiftmailer.transport'));
    }

    /**
     * Gets the 'monolog.handler.main' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Monolog\Handler\FingersCrossedHandler A Monolog\Handler\FingersCrossedHandler instance.
     */
    protected function getMonolog_Handler_MainService()
    {
        return $this->services['monolog.handler.main'] = new \Monolog\Handler\FingersCrossedHandler($this->get('monolog.handler.nested'), 400, 0, true, true);
    }

    /**
     * Gets the 'monolog.handler.nested' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Monolog\Handler\StreamHandler A Monolog\Handler\StreamHandler instance.
     */
    protected function getMonolog_Handler_NestedService()
    {
        return $this->services['monolog.handler.nested'] = new \Monolog\Handler\StreamHandler('C:/xampp/htdocs/beta-buggl/app/logs/prod.log', 100, true);
    }

    /**
     * Gets the 'monolog.logger.doctrine' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bridge\Monolog\Logger A Symfony\Bridge\Monolog\Logger instance.
     */
    protected function getMonolog_Logger_DoctrineService()
    {
        $this->services['monolog.logger.doctrine'] = $instance = new \Symfony\Bridge\Monolog\Logger('doctrine');

        $instance->pushHandler($this->get('monolog.handler.main'));

        return $instance;
    }

    /**
     * Gets the 'monolog.logger.event' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bridge\Monolog\Logger A Symfony\Bridge\Monolog\Logger instance.
     */
    protected function getMonolog_Logger_EventService()
    {
        $this->services['monolog.logger.event'] = $instance = new \Symfony\Bridge\Monolog\Logger('event');

        $instance->pushHandler($this->get('monolog.handler.main'));

        return $instance;
    }

    /**
     * Gets the 'monolog.logger.request' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bridge\Monolog\Logger A Symfony\Bridge\Monolog\Logger instance.
     */
    protected function getMonolog_Logger_RequestService()
    {
        $this->services['monolog.logger.request'] = $instance = new \Symfony\Bridge\Monolog\Logger('request');

        $instance->pushHandler($this->get('monolog.handler.main'));

        return $instance;
    }

    /**
     * Gets the 'monolog.logger.router' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bridge\Monolog\Logger A Symfony\Bridge\Monolog\Logger instance.
     */
    protected function getMonolog_Logger_RouterService()
    {
        $this->services['monolog.logger.router'] = $instance = new \Symfony\Bridge\Monolog\Logger('router');

        $instance->pushHandler($this->get('monolog.handler.main'));

        return $instance;
    }

    /**
     * Gets the 'monolog.logger.security' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bridge\Monolog\Logger A Symfony\Bridge\Monolog\Logger instance.
     */
    protected function getMonolog_Logger_SecurityService()
    {
        $this->services['monolog.logger.security'] = $instance = new \Symfony\Bridge\Monolog\Logger('security');

        $instance->pushHandler($this->get('monolog.handler.main'));

        return $instance;
    }

    /**
     * Gets the 'monolog.logger.snappy' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bridge\Monolog\Logger A Symfony\Bridge\Monolog\Logger instance.
     */
    protected function getMonolog_Logger_SnappyService()
    {
        $this->services['monolog.logger.snappy'] = $instance = new \Symfony\Bridge\Monolog\Logger('snappy');

        $instance->pushHandler($this->get('monolog.handler.main'));

        return $instance;
    }

    /**
     * Gets the 'monolog.logger.templating' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bridge\Monolog\Logger A Symfony\Bridge\Monolog\Logger instance.
     */
    protected function getMonolog_Logger_TemplatingService()
    {
        $this->services['monolog.logger.templating'] = $instance = new \Symfony\Bridge\Monolog\Logger('templating');

        $instance->pushHandler($this->get('monolog.handler.main'));

        return $instance;
    }

    /**
     * Gets the 'request' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getRequestService()
    {
        if (!isset($this->scopedServices['request'])) {
            throw new InactiveScopeException('request', 'request');
        }

        throw new RuntimeException('You have requested a synthetic service ("request"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the 'response_listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpKernel\EventListener\ResponseListener A Symfony\Component\HttpKernel\EventListener\ResponseListener instance.
     */
    protected function getResponseListenerService()
    {
        return $this->services['response_listener'] = new \Symfony\Component\HttpKernel\EventListener\ResponseListener('UTF-8');
    }

    /**
     * Gets the 'router' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Routing\Router A Symfony\Bundle\FrameworkBundle\Routing\Router instance.
     */
    protected function getRouterService()
    {
        return $this->services['router'] = new \Symfony\Bundle\FrameworkBundle\Routing\Router($this, 'C:/xampp/htdocs/beta-buggl/app/cache/prod/assetic/routing.yml', array('cache_dir' => 'C:/xampp/htdocs/beta-buggl/app/cache/prod', 'debug' => true, 'generator_class' => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator', 'generator_base_class' => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator', 'generator_dumper_class' => 'Symfony\\Component\\Routing\\Generator\\Dumper\\PhpGeneratorDumper', 'generator_cache_class' => 'appProdUrlGenerator', 'matcher_class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\RedirectableUrlMatcher', 'matcher_base_class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\RedirectableUrlMatcher', 'matcher_dumper_class' => 'Symfony\\Component\\Routing\\Matcher\\Dumper\\PhpMatcherDumper', 'matcher_cache_class' => 'appProdUrlMatcher', 'strict_requirements' => true), $this->get('router.request_context'), $this->get('monolog.logger.router'));
    }

    /**
     * Gets the 'router_listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpKernel\EventListener\RouterListener A Symfony\Component\HttpKernel\EventListener\RouterListener instance.
     */
    protected function getRouterListenerService()
    {
        return $this->services['router_listener'] = new \Symfony\Component\HttpKernel\EventListener\RouterListener($this->get('router'), $this->get('router.request_context'), $this->get('monolog.logger.request'));
    }

    /**
     * Gets the 'routing.loader' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader A Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader instance.
     */
    protected function getRouting_LoaderService()
    {
        $a = $this->get('file_locator');
        $b = $this->get('annotation_reader');

        $c = new \Sensio\Bundle\FrameworkExtraBundle\Routing\AnnotatedRouteControllerLoader($b);

        $d = new \Symfony\Component\Config\Loader\LoaderResolver();
        $d->addLoader(new \Symfony\Component\Routing\Loader\XmlFileLoader($a));
        $d->addLoader(new \Symfony\Component\Routing\Loader\YamlFileLoader($a));
        $d->addLoader(new \Symfony\Component\Routing\Loader\PhpFileLoader($a));
        $d->addLoader(new \Symfony\Bundle\AsseticBundle\Routing\AsseticLoader($this->get('assetic.asset_manager'), array()));
        $d->addLoader(new \Symfony\Component\Routing\Loader\AnnotationDirectoryLoader($a, $c));
        $d->addLoader(new \Symfony\Component\Routing\Loader\AnnotationFileLoader($a, $c));
        $d->addLoader($c);

        return $this->services['routing.loader'] = new \Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader($this->get('controller_name_converter'), $this->get('monolog.logger.router'), $d);
    }

    /**
     * Gets the 'security.access.decision_manager' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return JMS\SecurityExtraBundle\Security\Authorization\RememberingAccessDecisionManager A JMS\SecurityExtraBundle\Security\Authorization\RememberingAccessDecisionManager instance.
     */
    protected function getSecurity_Access_DecisionManagerService()
    {
        return $this->services['security.access.decision_manager'] = new \JMS\SecurityExtraBundle\Security\Authorization\RememberingAccessDecisionManager(new \Symfony\Component\Security\Core\Authorization\AccessDecisionManager(array(0 => new \Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter($this->get('security.role_hierarchy')), 1 => new \Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter($this->get('security.authentication.trust_resolver'))), 'affirmative', false, true));
    }

    /**
     * Gets the 'security.access.method_interceptor' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return JMS\SecurityExtraBundle\Security\Authorization\Interception\MethodSecurityInterceptor A JMS\SecurityExtraBundle\Security\Authorization\Interception\MethodSecurityInterceptor instance.
     */
    protected function getSecurity_Access_MethodInterceptorService()
    {
        return $this->services['security.access.method_interceptor'] = new \JMS\SecurityExtraBundle\Security\Authorization\Interception\MethodSecurityInterceptor($this->get('security.context'), $this->get('security.authentication.manager'), $this->get('security.access.decision_manager'), new \JMS\SecurityExtraBundle\Security\Authorization\AfterInvocation\AfterInvocationManager(array()), new \JMS\SecurityExtraBundle\Security\Authorization\RunAsManager('RunAsToken', 'ROLE_'), $this->get('security.extra.metadata_factory'), $this->get('monolog.logger.security'));
    }

    /**
     * Gets the 'security.access.pointcut' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return JMS\SecurityExtraBundle\Security\Authorization\Interception\SecurityPointcut A JMS\SecurityExtraBundle\Security\Authorization\Interception\SecurityPointcut instance.
     */
    protected function getSecurity_Access_PointcutService()
    {
        $this->services['security.access.pointcut'] = $instance = new \JMS\SecurityExtraBundle\Security\Authorization\Interception\SecurityPointcut($this->get('security.extra.metadata_factory'), false, array());

        $instance->setSecuredClasses(array());

        return $instance;
    }

    /**
     * Gets the 'security.context' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Security\Core\SecurityContext A Symfony\Component\Security\Core\SecurityContext instance.
     */
    protected function getSecurity_ContextService()
    {
        return $this->services['security.context'] = new \Symfony\Component\Security\Core\SecurityContext($this->get('security.authentication.manager'), $this->get('security.access.decision_manager'), false);
    }

    /**
     * Gets the 'security.encoder_factory' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Security\Core\Encoder\EncoderFactory A Symfony\Component\Security\Core\Encoder\EncoderFactory instance.
     */
    protected function getSecurity_EncoderFactoryService()
    {
        return $this->services['security.encoder_factory'] = new \Symfony\Component\Security\Core\Encoder\EncoderFactory(array('Buggl\\MainBundle\\Entity\\LocalAuthor' => array('class' => 'Symfony\\Component\\Security\\Core\\Encoder\\MessageDigestPasswordEncoder', 'arguments' => array(0 => 'sha512', 1 => true, 2 => 5000)), 'Buggl\\MainBundle\\Entity\\AdminUsers' => array('class' => 'Symfony\\Component\\Security\\Core\\Encoder\\MessageDigestPasswordEncoder', 'arguments' => array(0 => 'sha512', 1 => true, 2 => 5000))));
    }

    /**
     * Gets the 'security.extra.metadata_driver' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Metadata\Driver\DriverChain A Metadata\Driver\DriverChain instance.
     */
    protected function getSecurity_Extra_MetadataDriverService()
    {
        return $this->services['security.extra.metadata_driver'] = new \Metadata\Driver\DriverChain(array(0 => new \JMS\SecurityExtraBundle\Metadata\Driver\AnnotationDriver($this->get('annotation_reader'))));
    }

    /**
     * Gets the 'security.firewall' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Security\Http\Firewall A Symfony\Component\Security\Http\Firewall instance.
     */
    protected function getSecurity_FirewallService()
    {
        return $this->services['security.firewall'] = new \Symfony\Component\Security\Http\Firewall(new \Symfony\Bundle\SecurityBundle\Security\FirewallMap($this, array('security.firewall.map.context.dev' => new \Symfony\Component\HttpFoundation\RequestMatcher('^/(_(profiler|wdt)|css|images|js)/'), 'security.firewall.map.context.betea' => new \Symfony\Component\HttpFoundation\RequestMatcher('/beta-login'), 'security.firewall.map.context.secured_area' => new \Symfony\Component\HttpFoundation\RequestMatcher('^/(?!admin)'), 'security.firewall.map.context.admin_area' => new \Symfony\Component\HttpFoundation\RequestMatcher('/admin'))), $this->get('event_dispatcher'));
    }

    /**
     * Gets the 'security.firewall.map.context.admin_area' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\SecurityBundle\Security\FirewallContext A Symfony\Bundle\SecurityBundle\Security\FirewallContext instance.
     */
    protected function getSecurity_Firewall_Map_Context_AdminAreaService()
    {
        $a = $this->get('security.context');
        $b = $this->get('monolog.logger.security');
        $c = $this->get('event_dispatcher');
        $d = $this->get('security.http_utils');
        $e = $this->get('buggl_main.admin_login_handler');

        $f = new \Symfony\Component\Security\Http\Firewall\LogoutListener($a, $d, new \Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler($d, '/admin'), array('csrf_parameter' => '_csrf_token', 'intention' => 'logout', 'logout_path' => '/admin/logout'));
        $f->addHandler($this->get('security.logout.handler.session'));

        return $this->services['security.firewall.map.context.admin_area'] = new \Symfony\Bundle\SecurityBundle\Security\FirewallContext(array(0 => $this->get('security.channel_listener'), 1 => new \Symfony\Component\Security\Http\Firewall\ContextListener($a, array(0 => $this->get('security.user.provider.concrete.main'), 1 => $this->get('security.user.provider.concrete.admin')), 'admin_area', $b, $c), 2 => $f, 3 => new \Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener($a, $this->get('security.authentication.manager'), $this->get('security.authentication.session_strategy'), $d, 'admin_area', $e, $e, array('check_path' => '/admin/login_check', 'use_forward' => true, 'username_parameter' => '_username', 'password_parameter' => '_password', 'csrf_parameter' => '_csrf_token', 'intention' => 'authenticate', 'post_only' => true), $b, $c), 4 => $this->get('security.access_listener')), new \Symfony\Component\Security\Http\Firewall\ExceptionListener($a, $this->get('security.authentication.trust_resolver'), $d, 'admin_area', new \Symfony\Component\Security\Http\EntryPoint\FormAuthenticationEntryPoint($this->get('http_kernel'), $d, '/admin/login', true), NULL, NULL, $b));
    }

    /**
     * Gets the 'security.firewall.map.context.betea' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\SecurityBundle\Security\FirewallContext A Symfony\Bundle\SecurityBundle\Security\FirewallContext instance.
     */
    protected function getSecurity_Firewall_Map_Context_BeteaService()
    {
        return $this->services['security.firewall.map.context.betea'] = new \Symfony\Bundle\SecurityBundle\Security\FirewallContext(array(), NULL);
    }

    /**
     * Gets the 'security.firewall.map.context.dev' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\SecurityBundle\Security\FirewallContext A Symfony\Bundle\SecurityBundle\Security\FirewallContext instance.
     */
    protected function getSecurity_Firewall_Map_Context_DevService()
    {
        return $this->services['security.firewall.map.context.dev'] = new \Symfony\Bundle\SecurityBundle\Security\FirewallContext(array(), NULL);
    }

    /**
     * Gets the 'security.firewall.map.context.secured_area' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\SecurityBundle\Security\FirewallContext A Symfony\Bundle\SecurityBundle\Security\FirewallContext instance.
     */
    protected function getSecurity_Firewall_Map_Context_SecuredAreaService()
    {
        $a = $this->get('security.context');
        $b = $this->get('monolog.logger.security');
        $c = $this->get('event_dispatcher');
        $d = $this->get('security.http_utils');
        $e = $this->get('buggl_main.login_handler');

        $f = new \Symfony\Component\Security\Http\Firewall\LogoutListener($a, $d, new \Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler($d, '/'), array('csrf_parameter' => '_csrf_token', 'intention' => 'logout', 'logout_path' => '/logout'));
        $f->addHandler($this->get('security.logout.handler.session'));

        return $this->services['security.firewall.map.context.secured_area'] = new \Symfony\Bundle\SecurityBundle\Security\FirewallContext(array(0 => $this->get('security.channel_listener'), 1 => new \Symfony\Component\Security\Http\Firewall\ContextListener($a, array(0 => $this->get('security.user.provider.concrete.main'), 1 => $this->get('security.user.provider.concrete.admin')), 'secured_area', $b, $c), 2 => $f, 3 => new \Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener($a, $this->get('security.authentication.manager'), $this->get('security.authentication.session_strategy'), $d, 'secured_area', $e, $e, array('check_path' => '/login_check', 'use_forward' => true, 'username_parameter' => '_username', 'password_parameter' => '_password', 'csrf_parameter' => '_csrf_token', 'intention' => 'authenticate', 'post_only' => true), $b, $c), 4 => new \Symfony\Component\Security\Http\Firewall\AnonymousAuthenticationListener($a, '566ea66f01556', $b), 5 => $this->get('security.access_listener')), new \Symfony\Component\Security\Http\Firewall\ExceptionListener($a, $this->get('security.authentication.trust_resolver'), $d, 'secured_area', new \Symfony\Component\Security\Http\EntryPoint\FormAuthenticationEntryPoint($this->get('http_kernel'), $d, '/login', true), NULL, NULL, $b));
    }

    /**
     * Gets the 'security.rememberme.response_listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Security\Http\RememberMe\ResponseListener A Symfony\Component\Security\Http\RememberMe\ResponseListener instance.
     */
    protected function getSecurity_Rememberme_ResponseListenerService()
    {
        return $this->services['security.rememberme.response_listener'] = new \Symfony\Component\Security\Http\RememberMe\ResponseListener();
    }

    /**
     * Gets the 'security.role_hierarchy' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Security\Core\Role\RoleHierarchy A Symfony\Component\Security\Core\Role\RoleHierarchy instance.
     */
    protected function getSecurity_RoleHierarchyService()
    {
        return $this->services['security.role_hierarchy'] = new \Symfony\Component\Security\Core\Role\RoleHierarchy(array());
    }

    /**
     * Gets the 'security.validator.user_password' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Security\Core\Validator\Constraint\UserPasswordValidator A Symfony\Component\Security\Core\Validator\Constraint\UserPasswordValidator instance.
     */
    protected function getSecurity_Validator_UserPasswordService()
    {
        return $this->services['security.validator.user_password'] = new \Symfony\Component\Security\Core\Validator\Constraint\UserPasswordValidator($this->get('security.context'), $this->get('security.encoder_factory'));
    }

    /**
     * Gets the 'sensio_framework_extra.cache.listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Sensio\Bundle\FrameworkExtraBundle\EventListener\CacheListener A Sensio\Bundle\FrameworkExtraBundle\EventListener\CacheListener instance.
     */
    protected function getSensioFrameworkExtra_Cache_ListenerService()
    {
        return $this->services['sensio_framework_extra.cache.listener'] = new \Sensio\Bundle\FrameworkExtraBundle\EventListener\CacheListener();
    }

    /**
     * Gets the 'sensio_framework_extra.controller.listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Sensio\Bundle\FrameworkExtraBundle\EventListener\ControllerListener A Sensio\Bundle\FrameworkExtraBundle\EventListener\ControllerListener instance.
     */
    protected function getSensioFrameworkExtra_Controller_ListenerService()
    {
        return $this->services['sensio_framework_extra.controller.listener'] = new \Sensio\Bundle\FrameworkExtraBundle\EventListener\ControllerListener($this->get('annotation_reader'));
    }

    /**
     * Gets the 'sensio_framework_extra.converter.datetime' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DateTimeParamConverter A Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DateTimeParamConverter instance.
     */
    protected function getSensioFrameworkExtra_Converter_DatetimeService()
    {
        return $this->services['sensio_framework_extra.converter.datetime'] = new \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DateTimeParamConverter();
    }

    /**
     * Gets the 'sensio_framework_extra.converter.doctrine.orm' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter A Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter instance.
     */
    protected function getSensioFrameworkExtra_Converter_Doctrine_OrmService()
    {
        return $this->services['sensio_framework_extra.converter.doctrine.orm'] = new \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter($this->get('doctrine'));
    }

    /**
     * Gets the 'sensio_framework_extra.converter.listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Sensio\Bundle\FrameworkExtraBundle\EventListener\ParamConverterListener A Sensio\Bundle\FrameworkExtraBundle\EventListener\ParamConverterListener instance.
     */
    protected function getSensioFrameworkExtra_Converter_ListenerService()
    {
        return $this->services['sensio_framework_extra.converter.listener'] = new \Sensio\Bundle\FrameworkExtraBundle\EventListener\ParamConverterListener($this->get('sensio_framework_extra.converter.manager'));
    }

    /**
     * Gets the 'sensio_framework_extra.converter.manager' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterManager A Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterManager instance.
     */
    protected function getSensioFrameworkExtra_Converter_ManagerService()
    {
        $this->services['sensio_framework_extra.converter.manager'] = $instance = new \Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterManager();

        $instance->add($this->get('sensio_framework_extra.converter.doctrine.orm'), 0, 'doctrine.orm');
        $instance->add($this->get('sensio_framework_extra.converter.datetime'), 0, 'datetime');

        return $instance;
    }

    /**
     * Gets the 'sensio_framework_extra.view.guesser' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Sensio\Bundle\FrameworkExtraBundle\Templating\TemplateGuesser A Sensio\Bundle\FrameworkExtraBundle\Templating\TemplateGuesser instance.
     */
    protected function getSensioFrameworkExtra_View_GuesserService()
    {
        return $this->services['sensio_framework_extra.view.guesser'] = new \Sensio\Bundle\FrameworkExtraBundle\Templating\TemplateGuesser($this->get('kernel'));
    }

    /**
     * Gets the 'sensio_framework_extra.view.listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Sensio\Bundle\FrameworkExtraBundle\EventListener\TemplateListener A Sensio\Bundle\FrameworkExtraBundle\EventListener\TemplateListener instance.
     */
    protected function getSensioFrameworkExtra_View_ListenerService()
    {
        return $this->services['sensio_framework_extra.view.listener'] = new \Sensio\Bundle\FrameworkExtraBundle\EventListener\TemplateListener($this);
    }

    /**
     * Gets the 'service_container' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getServiceContainerService()
    {
        throw new RuntimeException('You have requested a synthetic service ("service_container"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the 'session' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpFoundation\Session\Session A Symfony\Component\HttpFoundation\Session\Session instance.
     */
    protected function getSessionService()
    {
        return $this->services['session'] = new \Symfony\Component\HttpFoundation\Session\Session($this->get('session.storage.native'), new \Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag(), new \Symfony\Component\HttpFoundation\Session\Flash\FlashBag());
    }

    /**
     * Gets the 'session.handler' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler A Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler instance.
     */
    protected function getSession_HandlerService()
    {
        return $this->services['session.handler'] = new \Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler(NULL);
    }

    /**
     * Gets the 'session.storage.filesystem' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage A Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage instance.
     */
    protected function getSession_Storage_FilesystemService()
    {
        return $this->services['session.storage.filesystem'] = new \Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage('C:/xampp/htdocs/beta-buggl/app/cache/prod/sessions');
    }

    /**
     * Gets the 'session.storage.native' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage A Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage instance.
     */
    protected function getSession_Storage_NativeService()
    {
        return $this->services['session.storage.native'] = new \Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage(array(), $this->get('session.handler'));
    }

    /**
     * Gets the 'session_listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\EventListener\SessionListener A Symfony\Bundle\FrameworkBundle\EventListener\SessionListener instance.
     */
    protected function getSessionListenerService()
    {
        return $this->services['session_listener'] = new \Symfony\Bundle\FrameworkBundle\EventListener\SessionListener($this);
    }

    /**
     * Gets the 'streamed_response_listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpKernel\EventListener\StreamedResponseListener A Symfony\Component\HttpKernel\EventListener\StreamedResponseListener instance.
     */
    protected function getStreamedResponseListenerService()
    {
        return $this->services['streamed_response_listener'] = new \Symfony\Component\HttpKernel\EventListener\StreamedResponseListener();
    }

    /**
     * Gets the 'swiftmailer.email_sender.listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\SwiftmailerBundle\EventListener\EmailSenderListener A Symfony\Bundle\SwiftmailerBundle\EventListener\EmailSenderListener instance.
     */
    protected function getSwiftmailer_EmailSender_ListenerService()
    {
        return $this->services['swiftmailer.email_sender.listener'] = new \Symfony\Bundle\SwiftmailerBundle\EventListener\EmailSenderListener($this);
    }

    /**
     * Gets the 'swiftmailer.plugin.messagelogger' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Swift_Plugins_MessageLogger A Swift_Plugins_MessageLogger instance.
     */
    protected function getSwiftmailer_Plugin_MessageloggerService()
    {
        return $this->services['swiftmailer.plugin.messagelogger'] = new \Swift_Plugins_MessageLogger();
    }

    /**
     * Gets the 'swiftmailer.spool' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Swift_MemorySpool A Swift_MemorySpool instance.
     */
    protected function getSwiftmailer_SpoolService()
    {
        return $this->services['swiftmailer.spool'] = new \Swift_MemorySpool();
    }

    /**
     * Gets the 'swiftmailer.transport' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Swift_Transport_SpoolTransport A Swift_Transport_SpoolTransport instance.
     */
    protected function getSwiftmailer_TransportService()
    {
        $this->services['swiftmailer.transport'] = $instance = new \Swift_Transport_SpoolTransport($this->get('swiftmailer.transport.eventdispatcher'), $this->get('swiftmailer.spool'));

        $instance->registerPlugin($this->get('swiftmailer.plugin.messagelogger'));

        return $instance;
    }

    /**
     * Gets the 'swiftmailer.transport.real' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Swift_Transport_EsmtpTransport A Swift_Transport_EsmtpTransport instance.
     */
    protected function getSwiftmailer_Transport_RealService()
    {
        $this->services['swiftmailer.transport.real'] = $instance = new \Swift_Transport_EsmtpTransport(new \Swift_Transport_StreamBuffer(new \Swift_StreamFilters_StringReplacementFilterFactory()), array(0 => new \Swift_Transport_Esmtp_AuthHandler(array(0 => new \Swift_Transport_Esmtp_Auth_CramMd5Authenticator(), 1 => new \Swift_Transport_Esmtp_Auth_LoginAuthenticator(), 2 => new \Swift_Transport_Esmtp_Auth_PlainAuthenticator()))), $this->get('swiftmailer.transport.eventdispatcher'));

        $instance->setHost('smtp.gmail.com');
        $instance->setPort(465);
        $instance->setEncryption('ssl');
        $instance->setUsername('admin@buggl.com');
        $instance->setPassword('sleeper16');
        $instance->setAuthMode('login');
        $instance->setTimeout(30);
        $instance->setSourceIp(NULL);

        return $instance;
    }

    /**
     * Gets the 'templating' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine A Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine instance.
     */
    protected function getTemplatingService()
    {
        $this->services['templating'] = $instance = new \Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine($this->get('twig'), $this->get('templating.name_parser'), $this->get('templating.locator'), $this->get('debug.stopwatch'), $this->get('templating.globals'));

        $instance->setDefaultEscapingStrategy(array(0 => $instance, 1 => 'guessDefaultEscapingStrategy'));

        return $instance;
    }

    /**
     * Gets the 'templating.asset.package_factory' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Asset\PackageFactory A Symfony\Bundle\FrameworkBundle\Templating\Asset\PackageFactory instance.
     */
    protected function getTemplating_Asset_PackageFactoryService()
    {
        return $this->services['templating.asset.package_factory'] = new \Symfony\Bundle\FrameworkBundle\Templating\Asset\PackageFactory($this);
    }

    /**
     * Gets the 'templating.filename_parser' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\TemplateFilenameParser A Symfony\Bundle\FrameworkBundle\Templating\TemplateFilenameParser instance.
     */
    protected function getTemplating_FilenameParserService()
    {
        return $this->services['templating.filename_parser'] = new \Symfony\Bundle\FrameworkBundle\Templating\TemplateFilenameParser();
    }

    /**
     * Gets the 'templating.globals' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables A Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables instance.
     */
    protected function getTemplating_GlobalsService()
    {
        return $this->services['templating.globals'] = new \Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables($this);
    }

    /**
     * Gets the 'templating.helper.actions' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Helper\ActionsHelper A Symfony\Bundle\FrameworkBundle\Templating\Helper\ActionsHelper instance.
     */
    protected function getTemplating_Helper_ActionsService()
    {
        return $this->services['templating.helper.actions'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\ActionsHelper($this->get('http_kernel'));
    }

    /**
     * Gets the 'templating.helper.assets' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Templating\Helper\CoreAssetsHelper A Symfony\Component\Templating\Helper\CoreAssetsHelper instance.
     */
    protected function getTemplating_Helper_AssetsService()
    {
        if (!isset($this->scopedServices['request'])) {
            throw new InactiveScopeException('templating.helper.assets', 'request');
        }

        return $this->services['templating.helper.assets'] = $this->scopedServices['request']['templating.helper.assets'] = new \Symfony\Component\Templating\Helper\CoreAssetsHelper(new \Symfony\Bundle\FrameworkBundle\Templating\Asset\PathPackage($this->get('request'), NULL, '%s?%s'), array());
    }

    /**
     * Gets the 'templating.helper.code' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Helper\CodeHelper A Symfony\Bundle\FrameworkBundle\Templating\Helper\CodeHelper instance.
     */
    protected function getTemplating_Helper_CodeService()
    {
        return $this->services['templating.helper.code'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\CodeHelper(NULL, 'C:/xampp/htdocs/beta-buggl/app', 'UTF-8');
    }

    /**
     * Gets the 'templating.helper.form' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper A Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper instance.
     */
    protected function getTemplating_Helper_FormService()
    {
        $a = new \Symfony\Bundle\FrameworkBundle\Templating\PhpEngine($this->get('templating.name_parser'), $this, $this->get('templating.loader'), $this->get('templating.globals'));
        $a->setCharset('UTF-8');
        $a->setHelpers(array('slots' => 'templating.helper.slots', 'assets' => 'templating.helper.assets', 'request' => 'templating.helper.request', 'session' => 'templating.helper.session', 'router' => 'templating.helper.router', 'actions' => 'templating.helper.actions', 'code' => 'templating.helper.code', 'translator' => 'templating.helper.translator', 'form' => 'templating.helper.form', 'logout_url' => 'templating.helper.logout_url', 'security' => 'templating.helper.security', 'assetic' => 'assetic.helper.dynamic'));

        return $this->services['templating.helper.form'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper(new \Symfony\Component\Form\FormRenderer(new \Symfony\Component\Form\Extension\Templating\TemplatingRendererEngine($a, array(0 => 'FrameworkBundle:Form')), $this->get('form.csrf_provider')));
    }

    /**
     * Gets the 'templating.helper.logout_url' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\SecurityBundle\Templating\Helper\LogoutUrlHelper A Symfony\Bundle\SecurityBundle\Templating\Helper\LogoutUrlHelper instance.
     */
    protected function getTemplating_Helper_LogoutUrlService()
    {
        $this->services['templating.helper.logout_url'] = $instance = new \Symfony\Bundle\SecurityBundle\Templating\Helper\LogoutUrlHelper($this, $this->get('router'));

        $instance->registerListener('secured_area', '/logout', 'logout', '_csrf_token', NULL);
        $instance->registerListener('admin_area', '/admin/logout', 'logout', '_csrf_token', NULL);

        return $instance;
    }

    /**
     * Gets the 'templating.helper.request' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Helper\RequestHelper A Symfony\Bundle\FrameworkBundle\Templating\Helper\RequestHelper instance.
     */
    protected function getTemplating_Helper_RequestService()
    {
        return $this->services['templating.helper.request'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\RequestHelper($this->get('request'));
    }

    /**
     * Gets the 'templating.helper.router' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Helper\RouterHelper A Symfony\Bundle\FrameworkBundle\Templating\Helper\RouterHelper instance.
     */
    protected function getTemplating_Helper_RouterService()
    {
        return $this->services['templating.helper.router'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\RouterHelper($this->get('router'));
    }

    /**
     * Gets the 'templating.helper.security' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\SecurityBundle\Templating\Helper\SecurityHelper A Symfony\Bundle\SecurityBundle\Templating\Helper\SecurityHelper instance.
     */
    protected function getTemplating_Helper_SecurityService()
    {
        return $this->services['templating.helper.security'] = new \Symfony\Bundle\SecurityBundle\Templating\Helper\SecurityHelper($this->get('security.context'));
    }

    /**
     * Gets the 'templating.helper.session' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Helper\SessionHelper A Symfony\Bundle\FrameworkBundle\Templating\Helper\SessionHelper instance.
     */
    protected function getTemplating_Helper_SessionService()
    {
        return $this->services['templating.helper.session'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\SessionHelper($this->get('request'));
    }

    /**
     * Gets the 'templating.helper.slots' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Templating\Helper\SlotsHelper A Symfony\Component\Templating\Helper\SlotsHelper instance.
     */
    protected function getTemplating_Helper_SlotsService()
    {
        return $this->services['templating.helper.slots'] = new \Symfony\Component\Templating\Helper\SlotsHelper();
    }

    /**
     * Gets the 'templating.helper.translator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Helper\TranslatorHelper A Symfony\Bundle\FrameworkBundle\Templating\Helper\TranslatorHelper instance.
     */
    protected function getTemplating_Helper_TranslatorService()
    {
        return $this->services['templating.helper.translator'] = new \Symfony\Bundle\FrameworkBundle\Templating\Helper\TranslatorHelper($this->get('translator'));
    }

    /**
     * Gets the 'templating.loader' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Loader\FilesystemLoader A Symfony\Bundle\FrameworkBundle\Templating\Loader\FilesystemLoader instance.
     */
    protected function getTemplating_LoaderService()
    {
        return $this->services['templating.loader'] = new \Symfony\Bundle\FrameworkBundle\Templating\Loader\FilesystemLoader($this->get('templating.locator'));
    }

    /**
     * Gets the 'templating.name_parser' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\TemplateNameParser A Symfony\Bundle\FrameworkBundle\Templating\TemplateNameParser instance.
     */
    protected function getTemplating_NameParserService()
    {
        return $this->services['templating.name_parser'] = new \Symfony\Bundle\FrameworkBundle\Templating\TemplateNameParser($this->get('kernel'));
    }

    /**
     * Gets the 'translation.dumper.csv' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Dumper\CsvFileDumper A Symfony\Component\Translation\Dumper\CsvFileDumper instance.
     */
    protected function getTranslation_Dumper_CsvService()
    {
        return $this->services['translation.dumper.csv'] = new \Symfony\Component\Translation\Dumper\CsvFileDumper();
    }

    /**
     * Gets the 'translation.dumper.ini' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Dumper\IniFileDumper A Symfony\Component\Translation\Dumper\IniFileDumper instance.
     */
    protected function getTranslation_Dumper_IniService()
    {
        return $this->services['translation.dumper.ini'] = new \Symfony\Component\Translation\Dumper\IniFileDumper();
    }

    /**
     * Gets the 'translation.dumper.mo' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Dumper\MoFileDumper A Symfony\Component\Translation\Dumper\MoFileDumper instance.
     */
    protected function getTranslation_Dumper_MoService()
    {
        return $this->services['translation.dumper.mo'] = new \Symfony\Component\Translation\Dumper\MoFileDumper();
    }

    /**
     * Gets the 'translation.dumper.php' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Dumper\PhpFileDumper A Symfony\Component\Translation\Dumper\PhpFileDumper instance.
     */
    protected function getTranslation_Dumper_PhpService()
    {
        return $this->services['translation.dumper.php'] = new \Symfony\Component\Translation\Dumper\PhpFileDumper();
    }

    /**
     * Gets the 'translation.dumper.po' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Dumper\PoFileDumper A Symfony\Component\Translation\Dumper\PoFileDumper instance.
     */
    protected function getTranslation_Dumper_PoService()
    {
        return $this->services['translation.dumper.po'] = new \Symfony\Component\Translation\Dumper\PoFileDumper();
    }

    /**
     * Gets the 'translation.dumper.qt' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Dumper\QtFileDumper A Symfony\Component\Translation\Dumper\QtFileDumper instance.
     */
    protected function getTranslation_Dumper_QtService()
    {
        return $this->services['translation.dumper.qt'] = new \Symfony\Component\Translation\Dumper\QtFileDumper();
    }

    /**
     * Gets the 'translation.dumper.res' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Dumper\IcuResFileDumper A Symfony\Component\Translation\Dumper\IcuResFileDumper instance.
     */
    protected function getTranslation_Dumper_ResService()
    {
        return $this->services['translation.dumper.res'] = new \Symfony\Component\Translation\Dumper\IcuResFileDumper();
    }

    /**
     * Gets the 'translation.dumper.xliff' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Dumper\XliffFileDumper A Symfony\Component\Translation\Dumper\XliffFileDumper instance.
     */
    protected function getTranslation_Dumper_XliffService()
    {
        return $this->services['translation.dumper.xliff'] = new \Symfony\Component\Translation\Dumper\XliffFileDumper();
    }

    /**
     * Gets the 'translation.dumper.yml' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Dumper\YamlFileDumper A Symfony\Component\Translation\Dumper\YamlFileDumper instance.
     */
    protected function getTranslation_Dumper_YmlService()
    {
        return $this->services['translation.dumper.yml'] = new \Symfony\Component\Translation\Dumper\YamlFileDumper();
    }

    /**
     * Gets the 'translation.extractor' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Extractor\ChainExtractor A Symfony\Component\Translation\Extractor\ChainExtractor instance.
     */
    protected function getTranslation_ExtractorService()
    {
        $this->services['translation.extractor'] = $instance = new \Symfony\Component\Translation\Extractor\ChainExtractor();

        $instance->addExtractor('php', $this->get('translation.extractor.php'));
        $instance->addExtractor('twig', $this->get('twig.translation.extractor'));

        return $instance;
    }

    /**
     * Gets the 'translation.extractor.php' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Translation\PhpExtractor A Symfony\Bundle\FrameworkBundle\Translation\PhpExtractor instance.
     */
    protected function getTranslation_Extractor_PhpService()
    {
        return $this->services['translation.extractor.php'] = new \Symfony\Bundle\FrameworkBundle\Translation\PhpExtractor();
    }

    /**
     * Gets the 'translation.loader' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Translation\TranslationLoader A Symfony\Bundle\FrameworkBundle\Translation\TranslationLoader instance.
     */
    protected function getTranslation_LoaderService()
    {
        $a = $this->get('translation.loader.xliff');

        $this->services['translation.loader'] = $instance = new \Symfony\Bundle\FrameworkBundle\Translation\TranslationLoader();

        $instance->addLoader('php', $this->get('translation.loader.php'));
        $instance->addLoader('yml', $this->get('translation.loader.yml'));
        $instance->addLoader('xlf', $a);
        $instance->addLoader('xliff', $a);
        $instance->addLoader('po', $this->get('translation.loader.po'));
        $instance->addLoader('mo', $this->get('translation.loader.mo'));
        $instance->addLoader('ts', $this->get('translation.loader.qt'));
        $instance->addLoader('csv', $this->get('translation.loader.csv'));
        $instance->addLoader('res', $this->get('translation.loader.res'));
        $instance->addLoader('dat', $this->get('translation.loader.dat'));
        $instance->addLoader('ini', $this->get('translation.loader.ini'));

        return $instance;
    }

    /**
     * Gets the 'translation.loader.csv' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Loader\CsvFileLoader A Symfony\Component\Translation\Loader\CsvFileLoader instance.
     */
    protected function getTranslation_Loader_CsvService()
    {
        return $this->services['translation.loader.csv'] = new \Symfony\Component\Translation\Loader\CsvFileLoader();
    }

    /**
     * Gets the 'translation.loader.dat' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Loader\IcuResFileLoader A Symfony\Component\Translation\Loader\IcuResFileLoader instance.
     */
    protected function getTranslation_Loader_DatService()
    {
        return $this->services['translation.loader.dat'] = new \Symfony\Component\Translation\Loader\IcuResFileLoader();
    }

    /**
     * Gets the 'translation.loader.ini' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Loader\IniFileLoader A Symfony\Component\Translation\Loader\IniFileLoader instance.
     */
    protected function getTranslation_Loader_IniService()
    {
        return $this->services['translation.loader.ini'] = new \Symfony\Component\Translation\Loader\IniFileLoader();
    }

    /**
     * Gets the 'translation.loader.mo' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Loader\MoFileLoader A Symfony\Component\Translation\Loader\MoFileLoader instance.
     */
    protected function getTranslation_Loader_MoService()
    {
        return $this->services['translation.loader.mo'] = new \Symfony\Component\Translation\Loader\MoFileLoader();
    }

    /**
     * Gets the 'translation.loader.php' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Loader\PhpFileLoader A Symfony\Component\Translation\Loader\PhpFileLoader instance.
     */
    protected function getTranslation_Loader_PhpService()
    {
        return $this->services['translation.loader.php'] = new \Symfony\Component\Translation\Loader\PhpFileLoader();
    }

    /**
     * Gets the 'translation.loader.po' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Loader\PoFileLoader A Symfony\Component\Translation\Loader\PoFileLoader instance.
     */
    protected function getTranslation_Loader_PoService()
    {
        return $this->services['translation.loader.po'] = new \Symfony\Component\Translation\Loader\PoFileLoader();
    }

    /**
     * Gets the 'translation.loader.qt' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Loader\QtTranslationsLoader A Symfony\Component\Translation\Loader\QtTranslationsLoader instance.
     */
    protected function getTranslation_Loader_QtService()
    {
        return $this->services['translation.loader.qt'] = new \Symfony\Component\Translation\Loader\QtTranslationsLoader();
    }

    /**
     * Gets the 'translation.loader.res' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Loader\IcuResFileLoader A Symfony\Component\Translation\Loader\IcuResFileLoader instance.
     */
    protected function getTranslation_Loader_ResService()
    {
        return $this->services['translation.loader.res'] = new \Symfony\Component\Translation\Loader\IcuResFileLoader();
    }

    /**
     * Gets the 'translation.loader.xliff' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Loader\XliffFileLoader A Symfony\Component\Translation\Loader\XliffFileLoader instance.
     */
    protected function getTranslation_Loader_XliffService()
    {
        return $this->services['translation.loader.xliff'] = new \Symfony\Component\Translation\Loader\XliffFileLoader();
    }

    /**
     * Gets the 'translation.loader.yml' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Loader\YamlFileLoader A Symfony\Component\Translation\Loader\YamlFileLoader instance.
     */
    protected function getTranslation_Loader_YmlService()
    {
        return $this->services['translation.loader.yml'] = new \Symfony\Component\Translation\Loader\YamlFileLoader();
    }

    /**
     * Gets the 'translation.writer' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\Writer\TranslationWriter A Symfony\Component\Translation\Writer\TranslationWriter instance.
     */
    protected function getTranslation_WriterService()
    {
        $this->services['translation.writer'] = $instance = new \Symfony\Component\Translation\Writer\TranslationWriter();

        $instance->addDumper('php', $this->get('translation.dumper.php'));
        $instance->addDumper('xlf', $this->get('translation.dumper.xliff'));
        $instance->addDumper('po', $this->get('translation.dumper.po'));
        $instance->addDumper('mo', $this->get('translation.dumper.mo'));
        $instance->addDumper('yml', $this->get('translation.dumper.yml'));
        $instance->addDumper('ts', $this->get('translation.dumper.qt'));
        $instance->addDumper('csv', $this->get('translation.dumper.csv'));
        $instance->addDumper('ini', $this->get('translation.dumper.ini'));
        $instance->addDumper('res', $this->get('translation.dumper.res'));

        return $instance;
    }

    /**
     * Gets the 'translator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Translation\IdentityTranslator A Symfony\Component\Translation\IdentityTranslator instance.
     */
    protected function getTranslatorService()
    {
        return $this->services['translator'] = new \Symfony\Component\Translation\IdentityTranslator($this->get('translator.selector'));
    }

    /**
     * Gets the 'translator.default' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\FrameworkBundle\Translation\Translator A Symfony\Bundle\FrameworkBundle\Translation\Translator instance.
     */
    protected function getTranslator_DefaultService()
    {
        return $this->services['translator.default'] = new \Symfony\Bundle\FrameworkBundle\Translation\Translator($this, $this->get('translator.selector'), array('translation.loader.php' => array(0 => 'php'), 'translation.loader.yml' => array(0 => 'yml'), 'translation.loader.xliff' => array(0 => 'xlf', 1 => 'xliff'), 'translation.loader.po' => array(0 => 'po'), 'translation.loader.mo' => array(0 => 'mo'), 'translation.loader.qt' => array(0 => 'ts'), 'translation.loader.csv' => array(0 => 'csv'), 'translation.loader.res' => array(0 => 'res'), 'translation.loader.dat' => array(0 => 'dat'), 'translation.loader.ini' => array(0 => 'ini')), array('cache_dir' => 'C:/xampp/htdocs/beta-buggl/app/cache/prod/translations', 'debug' => true));
    }

    /**
     * Gets the 'twig' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Twig_Environment A Twig_Environment instance.
     */
    protected function getTwigService()
    {
        $this->services['twig'] = $instance = new \Twig_Environment($this->get('twig.loader'), array('cache' => false, 'debug' => true, 'strict_variables' => true, 'exception_controller' => 'Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController::showAction', 'charset' => 'UTF-8', 'paths' => array()));

        $instance->addExtension(new \Symfony\Bundle\SecurityBundle\Twig\Extension\LogoutUrlExtension($this->get('templating.helper.logout_url')));
        $instance->addExtension(new \Symfony\Bridge\Twig\Extension\SecurityExtension($this->get('security.context')));
        $instance->addExtension(new \Symfony\Bridge\Twig\Extension\TranslationExtension($this->get('translator')));
        $instance->addExtension(new \Symfony\Bundle\TwigBundle\Extension\AssetsExtension($this));
        $instance->addExtension(new \Symfony\Bundle\TwigBundle\Extension\ActionsExtension($this));
        $instance->addExtension(new \Symfony\Bundle\TwigBundle\Extension\CodeExtension($this));
        $instance->addExtension(new \Symfony\Bridge\Twig\Extension\RoutingExtension($this->get('router')));
        $instance->addExtension(new \Symfony\Bridge\Twig\Extension\YamlExtension());
        $instance->addExtension(new \Symfony\Bridge\Twig\Extension\FormExtension(new \Symfony\Bridge\Twig\Form\TwigRenderer(new \Symfony\Bridge\Twig\Form\TwigRendererEngine(array(0 => 'form_div_layout.html.twig')), $this->get('form.csrf_provider'))));
        $instance->addExtension(new \Twig_Extension_Debug());
        $instance->addExtension(new \Symfony\Bundle\AsseticBundle\Twig\AsseticExtension($this->get('assetic.asset_factory'), $this->get('templating.name_parser'), true, array(), array(0 => 'BugglMainBundle', 1 => 'BugglPhotoBundle'), new \Symfony\Bundle\AsseticBundle\DefaultValueSupplier($this)));
        $instance->addExtension($this->get('buggl.twig.buggl_css_selected_extension'));
        $instance->addExtension($this->get('buggl.twig.buggl_travel_info_extension'));
        $instance->addExtension($this->get('buggl.twig.buggl_string_helper_extension'));
        $instance->addExtension($this->get('buggl.twig.buggl_slugify_extension'));
        $instance->addExtension($this->get('buggl.twig.buggl_query_extension'));
        $instance->addExtension($this->get('buggl.twig.buggl_date_helper_extension'));
        $instance->addExtension($this->get('buggl.twig.buggl_image_helper_extension'));
        $instance->addExtension($this->get('buggl.twig.buggl_message_helper_extension'));
        $instance->addExtension($this->get('buggl.twig.buggl_eguide_helper_extension'));
        $instance->addExtension($this->get('buggl.twig.buggl_activity_helper_extension'));
        $instance->addExtension($this->get('buggl.twig.buggl_constant_extension'));
        $instance->addExtension($this->get('buggl.twig.buggl_cdn_extension'));
        $instance->addGlobal('app', $this->get('templating.globals'));

        return $instance;
    }

    /**
     * Gets the 'twig.exception_listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\HttpKernel\EventListener\ExceptionListener A Symfony\Component\HttpKernel\EventListener\ExceptionListener instance.
     */
    protected function getTwig_ExceptionListenerService()
    {
        return $this->services['twig.exception_listener'] = new \Symfony\Component\HttpKernel\EventListener\ExceptionListener('Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController::showAction', $this->get('monolog.logger.request'));
    }

    /**
     * Gets the 'twig.loader' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bundle\TwigBundle\Loader\FilesystemLoader A Symfony\Bundle\TwigBundle\Loader\FilesystemLoader instance.
     */
    protected function getTwig_LoaderService()
    {
        $this->services['twig.loader'] = $instance = new \Symfony\Bundle\TwigBundle\Loader\FilesystemLoader($this->get('templating.locator'), $this->get('templating.name_parser'));

        $instance->addPath('C:\\xampp\\htdocs\\beta-buggl\\vendor\\symfony\\symfony\\src\\Symfony\\Bridge\\Twig/Resources/views/Form');

        return $instance;
    }

    /**
     * Gets the 'twig.translation.extractor' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Bridge\Twig\Translation\TwigExtractor A Symfony\Bridge\Twig\Translation\TwigExtractor instance.
     */
    protected function getTwig_Translation_ExtractorService()
    {
        return $this->services['twig.translation.extractor'] = new \Symfony\Bridge\Twig\Translation\TwigExtractor($this->get('twig'));
    }

    /**
     * Gets the 'validator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\Validator\Validator A Symfony\Component\Validator\Validator instance.
     */
    protected function getValidatorService()
    {
        return $this->services['validator'] = new \Symfony\Component\Validator\Validator($this->get('validator.mapping.class_metadata_factory'), new \Symfony\Bundle\FrameworkBundle\Validator\ConstraintValidatorFactory($this, array('security.validator.user_password' => 'security.validator.user_password', 'doctrine.orm.validator.unique' => 'doctrine.orm.validator.unique')), array(0 => $this->get('doctrine.orm.validator_initializer')));
    }

    /**
     * Gets the database_connection service alias.
     *
     * @return stdClass An instance of the doctrine.dbal.default_connection service
     */
    protected function getDatabaseConnectionService()
    {
        return $this->get('doctrine.dbal.default_connection');
    }

    /**
     * Gets the debug.event_dispatcher service alias.
     *
     * @return Symfony\Component\HttpKernel\Debug\ContainerAwareTraceableEventDispatcher An instance of the event_dispatcher service
     */
    protected function getDebug_EventDispatcherService()
    {
        return $this->get('event_dispatcher');
    }

    /**
     * Gets the debug.templating.engine.twig service alias.
     *
     * @return Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine An instance of the templating service
     */
    protected function getDebug_Templating_Engine_TwigService()
    {
        return $this->get('templating');
    }

    /**
     * Gets the doctrine.orm.entity_manager service alias.
     *
     * @return EntityManager566ea66f2289e_546a8d27f194334ee012bfe64f629947b07e4919\__CG__\Doctrine\ORM\EntityManager An instance of the doctrine.orm.default_entity_manager service
     */
    protected function getDoctrine_Orm_EntityManagerService()
    {
        return $this->get('doctrine.orm.default_entity_manager');
    }

    /**
     * Gets the session.storage service alias.
     *
     * @return Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage An instance of the session.storage.native service
     */
    protected function getSession_StorageService()
    {
        return $this->get('session.storage.native');
    }

    /**
     * Gets the 'assetic.asset_factory' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Bundle\AsseticBundle\Factory\AssetFactory A Symfony\Bundle\AsseticBundle\Factory\AssetFactory instance.
     */
    protected function getAssetic_AssetFactoryService()
    {
        $this->services['assetic.asset_factory'] = $instance = new \Symfony\Bundle\AsseticBundle\Factory\AssetFactory($this->get('kernel'), $this, $this->getParameterBag(), 'C:/xampp/htdocs/beta-buggl/app/../web', true);

        $instance->addWorker(new \Symfony\Bundle\AsseticBundle\Factory\Worker\UseControllerWorker());

        return $instance;
    }

    /**
     * Gets the 'assetic.cache' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Assetic\Cache\FilesystemCache A Assetic\Cache\FilesystemCache instance.
     */
    protected function getAssetic_CacheService()
    {
        return $this->services['assetic.cache'] = new \Assetic\Cache\FilesystemCache('C:/xampp/htdocs/beta-buggl/app/cache/prod/assetic/assets');
    }

    /**
     * Gets the 'controller_name_converter' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser A Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser instance.
     */
    protected function getControllerNameConverterService()
    {
        return $this->services['controller_name_converter'] = new \Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser($this->get('kernel'));
    }

    /**
     * Gets the 'router.request_context' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Component\Routing\RequestContext A Symfony\Component\Routing\RequestContext instance.
     */
    protected function getRouter_RequestContextService()
    {
        return $this->services['router.request_context'] = new \Symfony\Component\Routing\RequestContext('', 'GET', 'localhost', 'http', 80, 443);
    }

    /**
     * Gets the 'security.access_listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Component\Security\Http\Firewall\AccessListener A Symfony\Component\Security\Http\Firewall\AccessListener instance.
     */
    protected function getSecurity_AccessListenerService()
    {
        return $this->services['security.access_listener'] = new \Symfony\Component\Security\Http\Firewall\AccessListener($this->get('security.context'), $this->get('security.access.decision_manager'), $this->get('security.access_map'), $this->get('security.authentication.manager'), $this->get('monolog.logger.security'));
    }

    /**
     * Gets the 'security.access_map' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Component\Security\Http\AccessMap A Symfony\Component\Security\Http\AccessMap instance.
     */
    protected function getSecurity_AccessMapService()
    {
        $this->services['security.access_map'] = $instance = new \Symfony\Component\Security\Http\AccessMap();

        $instance->add(new \Symfony\Component\HttpFoundation\RequestMatcher('/references'), array(0 => 'ROLE_LOCAL_AUTHOR'), NULL);
        $instance->add(new \Symfony\Component\HttpFoundation\RequestMatcher('/e-guides/(?!purchased)'), array(0 => 'ROLE_LOCAL_AUTHOR'), NULL);
        $instance->add(new \Symfony\Component\HttpFoundation\RequestMatcher('/gallery-and-spots'), array(0 => 'ROLE_LOCAL_AUTHOR'), NULL);
        $instance->add(new \Symfony\Component\HttpFoundation\RequestMatcher('/add-travel-guide-info'), array(0 => 'ROLE_LOCAL_AUTHOR'), NULL);
        $instance->add(new \Symfony\Component\HttpFoundation\RequestMatcher('/travel-guide-info'), array(0 => 'ROLE_LOCAL_AUTHOR'), NULL);
        $instance->add(new \Symfony\Component\HttpFoundation\RequestMatcher('/earn-more'), array(0 => 'ROLE_LOCAL_AUTHOR'), NULL);
        $instance->add(new \Symfony\Component\HttpFoundation\RequestMatcher('/vouch'), array(0 => 'ROLE_LOCAL_AUTHOR'), NULL);
        $instance->add(new \Symfony\Component\HttpFoundation\RequestMatcher('^/(?!admin)'), array(0 => 'ROLE_LOCAL_AUTHOR', 1 => 'IS_AUTHENTICATED_ANONYMOUSLY'), NULL);
        $instance->add(new \Symfony\Component\HttpFoundation\RequestMatcher('/admin'), array(0 => 'ROLE_ADMIN', 1 => 'ROLE_SUPER_ADMIN'), NULL);

        return $instance;
    }

    /**
     * Gets the 'security.authentication.manager' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager A Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager instance.
     */
    protected function getSecurity_Authentication_ManagerService()
    {
        $a = $this->get('security.encoder_factory');

        $b = new \Symfony\Component\Security\Core\User\UserChecker();

        $this->services['security.authentication.manager'] = $instance = new \Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager(array(0 => new \Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider($this->get('security.user.provider.concrete.main'), $b, 'secured_area', $a, true), 1 => new \Symfony\Component\Security\Core\Authentication\Provider\AnonymousAuthenticationProvider('566ea66f01556'), 2 => new \Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider($this->get('security.user.provider.concrete.admin'), $b, 'admin_area', $a, true)), true);

        $instance->setEventDispatcher($this->get('event_dispatcher'));

        return $instance;
    }

    /**
     * Gets the 'security.authentication.session_strategy' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Component\Security\Http\Session\SessionAuthenticationStrategy A Symfony\Component\Security\Http\Session\SessionAuthenticationStrategy instance.
     */
    protected function getSecurity_Authentication_SessionStrategyService()
    {
        return $this->services['security.authentication.session_strategy'] = new \Symfony\Component\Security\Http\Session\SessionAuthenticationStrategy('migrate');
    }

    /**
     * Gets the 'security.authentication.trust_resolver' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolver A Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolver instance.
     */
    protected function getSecurity_Authentication_TrustResolverService()
    {
        return $this->services['security.authentication.trust_resolver'] = new \Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolver('Symfony\\Component\\Security\\Core\\Authentication\\Token\\AnonymousToken', 'Symfony\\Component\\Security\\Core\\Authentication\\Token\\RememberMeToken');
    }

    /**
     * Gets the 'security.channel_listener' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Component\Security\Http\Firewall\ChannelListener A Symfony\Component\Security\Http\Firewall\ChannelListener instance.
     */
    protected function getSecurity_ChannelListenerService()
    {
        return $this->services['security.channel_listener'] = new \Symfony\Component\Security\Http\Firewall\ChannelListener($this->get('security.access_map'), new \Symfony\Component\Security\Http\EntryPoint\RetryAuthenticationEntryPoint(80, 443), $this->get('monolog.logger.security'));
    }

    /**
     * Gets the 'security.extra.metadata_factory' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Metadata\MetadataFactory A Metadata\MetadataFactory instance.
     */
    protected function getSecurity_Extra_MetadataFactoryService()
    {
        $this->services['security.extra.metadata_factory'] = $instance = new \Metadata\MetadataFactory(new \Metadata\Driver\LazyLoadingDriver($this, 'security.extra.metadata_driver'));

        $instance->setCache(new \Metadata\Cache\FileCache('C:/xampp/htdocs/beta-buggl/app/cache/prod/jms_security', true));
        $instance->setIncludeInterfaces(true);

        return $instance;
    }

    /**
     * Gets the 'security.http_utils' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Component\Security\Http\HttpUtils A Symfony\Component\Security\Http\HttpUtils instance.
     */
    protected function getSecurity_HttpUtilsService()
    {
        $a = $this->get('router');

        return $this->services['security.http_utils'] = new \Symfony\Component\Security\Http\HttpUtils($a, $a);
    }

    /**
     * Gets the 'security.logout.handler.session' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Component\Security\Http\Logout\SessionLogoutHandler A Symfony\Component\Security\Http\Logout\SessionLogoutHandler instance.
     */
    protected function getSecurity_Logout_Handler_SessionService()
    {
        return $this->services['security.logout.handler.session'] = new \Symfony\Component\Security\Http\Logout\SessionLogoutHandler();
    }

    /**
     * Gets the 'security.user.provider.concrete.admin' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Bridge\Doctrine\Security\User\EntityUserProvider A Symfony\Bridge\Doctrine\Security\User\EntityUserProvider instance.
     */
    protected function getSecurity_User_Provider_Concrete_AdminService()
    {
        return $this->services['security.user.provider.concrete.admin'] = new \Symfony\Bridge\Doctrine\Security\User\EntityUserProvider($this->get('doctrine'), 'Buggl\\MainBundle\\Entity\\AdminUsers', 'email', NULL);
    }

    /**
     * Gets the 'security.user.provider.concrete.main' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Bridge\Doctrine\Security\User\EntityUserProvider A Symfony\Bridge\Doctrine\Security\User\EntityUserProvider instance.
     */
    protected function getSecurity_User_Provider_Concrete_MainService()
    {
        return $this->services['security.user.provider.concrete.main'] = new \Symfony\Bridge\Doctrine\Security\User\EntityUserProvider($this->get('doctrine'), 'Buggl\\MainBundle\\Entity\\LocalAuthor', 'email', NULL);
    }

    /**
     * Gets the 'swiftmailer.transport.eventdispatcher' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Swift_Events_SimpleEventDispatcher A Swift_Events_SimpleEventDispatcher instance.
     */
    protected function getSwiftmailer_Transport_EventdispatcherService()
    {
        return $this->services['swiftmailer.transport.eventdispatcher'] = new \Swift_Events_SimpleEventDispatcher();
    }

    /**
     * Gets the 'templating.locator' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator A Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator instance.
     */
    protected function getTemplating_LocatorService()
    {
        return $this->services['templating.locator'] = new \Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator($this->get('file_locator'), 'C:/xampp/htdocs/beta-buggl/app/cache/prod');
    }

    /**
     * Gets the 'translator.selector' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Component\Translation\MessageSelector A Symfony\Component\Translation\MessageSelector instance.
     */
    protected function getTranslator_SelectorService()
    {
        return $this->services['translator.selector'] = new \Symfony\Component\Translation\MessageSelector();
    }

    /**
     * Gets the 'validator.mapping.class_metadata_factory' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return Symfony\Component\Validator\Mapping\ClassMetadataFactory A Symfony\Component\Validator\Mapping\ClassMetadataFactory instance.
     */
    protected function getValidator_Mapping_ClassMetadataFactoryService()
    {
        return $this->services['validator.mapping.class_metadata_factory'] = new \Symfony\Component\Validator\Mapping\ClassMetadataFactory(new \Symfony\Component\Validator\Mapping\Loader\LoaderChain(array(0 => new \Symfony\Component\Validator\Mapping\Loader\AnnotationLoader($this->get('annotation_reader')), 1 => new \Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader(), 2 => new \Symfony\Component\Validator\Mapping\Loader\XmlFilesLoader(array(0 => 'C:\\xampp\\htdocs\\beta-buggl\\vendor\\symfony\\symfony\\src\\Symfony\\Component\\Form/Resources/config/validation.xml')), 3 => new \Symfony\Component\Validator\Mapping\Loader\YamlFilesLoader(array(0 => 'C:\\xampp\\htdocs\\beta-buggl\\src\\Buggl\\MainBundle\\Resources\\config\\validation.yml')))), NULL);
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name)
    {
        $name = strtolower($name);

        if (!array_key_exists($name, $this->parameters)) {
            throw new InvalidArgumentException(sprintf('The parameter "%s" must be defined.', $name));
        }

        return $this->parameters[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter($name)
    {
        return array_key_exists(strtolower($name), $this->parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter($name, $value)
    {
        throw new LogicException('Impossible to call set() on a frozen ParameterBag.');
    }

    /**
     * {@inheritDoc}
     */
    public function getParameterBag()
    {
        if (null === $this->parameterBag) {
            $this->parameterBag = new FrozenParameterBag($this->parameters);
        }

        return $this->parameterBag;
    }
    /**
     * Gets the default parameters.
     *
     * @return array An array of the default parameters
     */
    protected function getDefaultParameters()
    {
        return array(
            'kernel.root_dir' => 'C:/xampp/htdocs/beta-buggl/app',
            'kernel.environment' => 'prod',
            'kernel.debug' => true,
            'kernel.name' => 'app',
            'kernel.cache_dir' => 'C:/xampp/htdocs/beta-buggl/app/cache/prod',
            'kernel.logs_dir' => 'C:/xampp/htdocs/beta-buggl/app/logs',
            'kernel.bundles' => array(
                'KnpSnappyBundle' => 'Knp\\Bundle\\SnappyBundle\\KnpSnappyBundle',
                'FrameworkBundle' => 'Symfony\\Bundle\\FrameworkBundle\\FrameworkBundle',
                'SecurityBundle' => 'Symfony\\Bundle\\SecurityBundle\\SecurityBundle',
                'TwigBundle' => 'Symfony\\Bundle\\TwigBundle\\TwigBundle',
                'MonologBundle' => 'Symfony\\Bundle\\MonologBundle\\MonologBundle',
                'SwiftmailerBundle' => 'Symfony\\Bundle\\SwiftmailerBundle\\SwiftmailerBundle',
                'AsseticBundle' => 'Symfony\\Bundle\\AsseticBundle\\AsseticBundle',
                'DoctrineBundle' => 'Doctrine\\Bundle\\DoctrineBundle\\DoctrineBundle',
                'SensioFrameworkExtraBundle' => 'Sensio\\Bundle\\FrameworkExtraBundle\\SensioFrameworkExtraBundle',
                'JMSAopBundle' => 'JMS\\AopBundle\\JMSAopBundle',
                'JMSDiExtraBundle' => 'JMS\\DiExtraBundle\\JMSDiExtraBundle',
                'JMSSecurityExtraBundle' => 'JMS\\SecurityExtraBundle\\JMSSecurityExtraBundle',
                'BugglMainBundle' => 'Buggl\\MainBundle\\BugglMainBundle',
                'BugglPhotoBundle' => 'Buggl\\PhotoBundle\\BugglPhotoBundle',
            ),
            'kernel.charset' => 'UTF-8',
            'kernel.container_class' => 'appProdDebugProjectContainer',
            'database_driver' => 'pdo_mysql',
            'database_host' => 'localhost',
            'database_port' => NULL,
            'database_name' => 'buggl_beta_may31',
            'database_user' => 'buggl',
            'database_password' => 'FRJbXdmmHCUW5EjM',
            'mailer_transport' => 'gmail',
            'mailer_host' => 'smtp.gmail.com',
            'mailer_user' => 'admin@buggl.com',
            'mailer_password' => 'sleeper16',
            'locale' => 'en',
            'secret' => 'ThisTokenIsNotSoSecretChangeIt',
            'knp_snappy.pdf.internal_generator.class' => 'Knp\\Snappy\\Pdf',
            'knp_snappy.pdf.class' => 'Knp\\Bundle\\SnappyBundle\\Snappy\\LoggableGenerator',
            'knp_snappy.pdf.binary' => '/media/ebs1/websites/beta.buggl.com/wkhtmltopdf',
            'knp_snappy.pdf.options' => array(

            ),
            'knp_snappy.pdf.env' => array(

            ),
            'knp_snappy.image.internal_generator.class' => 'Knp\\Snappy\\Image',
            'knp_snappy.image.class' => 'Knp\\Bundle\\SnappyBundle\\Snappy\\LoggableGenerator',
            'knp_snappy.image.binary' => 'wkhtmltoimage',
            'knp_snappy.image.options' => array(

            ),
            'knp_snappy.image.env' => array(

            ),
            'controller_resolver.class' => 'Symfony\\Bundle\\FrameworkBundle\\Controller\\ControllerResolver',
            'controller_name_converter.class' => 'Symfony\\Bundle\\FrameworkBundle\\Controller\\ControllerNameParser',
            'response_listener.class' => 'Symfony\\Component\\HttpKernel\\EventListener\\ResponseListener',
            'streamed_response_listener.class' => 'Symfony\\Component\\HttpKernel\\EventListener\\StreamedResponseListener',
            'locale_listener.class' => 'Symfony\\Component\\HttpKernel\\EventListener\\LocaleListener',
            'event_dispatcher.class' => 'Symfony\\Component\\EventDispatcher\\ContainerAwareEventDispatcher',
            'http_kernel.class' => 'Symfony\\Bundle\\FrameworkBundle\\HttpKernel',
            'filesystem.class' => 'Symfony\\Component\\Filesystem\\Filesystem',
            'cache_warmer.class' => 'Symfony\\Component\\HttpKernel\\CacheWarmer\\CacheWarmerAggregate',
            'cache_clearer.class' => 'Symfony\\Component\\HttpKernel\\CacheClearer\\ChainCacheClearer',
            'file_locator.class' => 'Symfony\\Component\\HttpKernel\\Config\\FileLocator',
            'translator.class' => 'Symfony\\Bundle\\FrameworkBundle\\Translation\\Translator',
            'translator.identity.class' => 'Symfony\\Component\\Translation\\IdentityTranslator',
            'translator.selector.class' => 'Symfony\\Component\\Translation\\MessageSelector',
            'translation.loader.php.class' => 'Symfony\\Component\\Translation\\Loader\\PhpFileLoader',
            'translation.loader.yml.class' => 'Symfony\\Component\\Translation\\Loader\\YamlFileLoader',
            'translation.loader.xliff.class' => 'Symfony\\Component\\Translation\\Loader\\XliffFileLoader',
            'translation.loader.po.class' => 'Symfony\\Component\\Translation\\Loader\\PoFileLoader',
            'translation.loader.mo.class' => 'Symfony\\Component\\Translation\\Loader\\MoFileLoader',
            'translation.loader.qt.class' => 'Symfony\\Component\\Translation\\Loader\\QtTranslationsLoader',
            'translation.loader.csv.class' => 'Symfony\\Component\\Translation\\Loader\\CsvFileLoader',
            'translation.loader.res.class' => 'Symfony\\Component\\Translation\\Loader\\IcuResFileLoader',
            'translation.loader.dat.class' => 'Symfony\\Component\\Translation\\Loader\\IcuDatFileLoader',
            'translation.loader.ini.class' => 'Symfony\\Component\\Translation\\Loader\\IniFileLoader',
            'translation.dumper.php.class' => 'Symfony\\Component\\Translation\\Dumper\\PhpFileDumper',
            'translation.dumper.xliff.class' => 'Symfony\\Component\\Translation\\Dumper\\XliffFileDumper',
            'translation.dumper.po.class' => 'Symfony\\Component\\Translation\\Dumper\\PoFileDumper',
            'translation.dumper.mo.class' => 'Symfony\\Component\\Translation\\Dumper\\MoFileDumper',
            'translation.dumper.yml.class' => 'Symfony\\Component\\Translation\\Dumper\\YamlFileDumper',
            'translation.dumper.qt.class' => 'Symfony\\Component\\Translation\\Dumper\\QtFileDumper',
            'translation.dumper.csv.class' => 'Symfony\\Component\\Translation\\Dumper\\CsvFileDumper',
            'translation.dumper.ini.class' => 'Symfony\\Component\\Translation\\Dumper\\IniFileDumper',
            'translation.dumper.res.class' => 'Symfony\\Component\\Translation\\Dumper\\IcuResFileDumper',
            'translation.extractor.php.class' => 'Symfony\\Bundle\\FrameworkBundle\\Translation\\PhpExtractor',
            'translation.loader.class' => 'Symfony\\Bundle\\FrameworkBundle\\Translation\\TranslationLoader',
            'translation.extractor.class' => 'Symfony\\Component\\Translation\\Extractor\\ChainExtractor',
            'translation.writer.class' => 'Symfony\\Component\\Translation\\Writer\\TranslationWriter',
            'debug.event_dispatcher.class' => 'Symfony\\Component\\HttpKernel\\Debug\\ContainerAwareTraceableEventDispatcher',
            'debug.stopwatch.class' => 'Symfony\\Component\\HttpKernel\\Debug\\Stopwatch',
            'debug.container.dump' => 'C:/xampp/htdocs/beta-buggl/app/cache/prod/appProdDebugProjectContainer.xml',
            'debug.controller_resolver.class' => 'Symfony\\Bundle\\FrameworkBundle\\Controller\\TraceableControllerResolver',
            'kernel.secret' => 'ThisTokenIsNotSoSecretChangeIt',
            'kernel.trusted_proxies' => array(

            ),
            'kernel.trusted_hosts' => array(

            ),
            'kernel.trust_proxy_headers' => false,
            'kernel.default_locale' => 'en',
            'session.class' => 'Symfony\\Component\\HttpFoundation\\Session\\Session',
            'session.flashbag.class' => 'Symfony\\Component\\HttpFoundation\\Session\\Flash\\FlashBag',
            'session.attribute_bag.class' => 'Symfony\\Component\\HttpFoundation\\Session\\Attribute\\AttributeBag',
            'session.storage.native.class' => 'Symfony\\Component\\HttpFoundation\\Session\\Storage\\NativeSessionStorage',
            'session.storage.mock_file.class' => 'Symfony\\Component\\HttpFoundation\\Session\\Storage\\MockFileSessionStorage',
            'session.handler.native_file.class' => 'Symfony\\Component\\HttpFoundation\\Session\\Storage\\Handler\\NativeFileSessionHandler',
            'session_listener.class' => 'Symfony\\Bundle\\FrameworkBundle\\EventListener\\SessionListener',
            'session.storage.options' => array(

            ),
            'session.save_path' => NULL,
            'form.resolved_type_factory.class' => 'Symfony\\Component\\Form\\ResolvedFormTypeFactory',
            'form.registry.class' => 'Symfony\\Component\\Form\\FormRegistry',
            'form.factory.class' => 'Symfony\\Component\\Form\\FormFactory',
            'form.extension.class' => 'Symfony\\Component\\Form\\Extension\\DependencyInjection\\DependencyInjectionExtension',
            'form.type_guesser.validator.class' => 'Symfony\\Component\\Form\\Extension\\Validator\\ValidatorTypeGuesser',
            'form.csrf_provider.class' => 'Symfony\\Component\\Form\\Extension\\Csrf\\CsrfProvider\\SessionCsrfProvider',
            'form.type_extension.csrf.enabled' => true,
            'form.type_extension.csrf.field_name' => '_token',
            'validator.class' => 'Symfony\\Component\\Validator\\Validator',
            'validator.mapping.class_metadata_factory.class' => 'Symfony\\Component\\Validator\\Mapping\\ClassMetadataFactory',
            'validator.mapping.cache.apc.class' => 'Symfony\\Component\\Validator\\Mapping\\Cache\\ApcCache',
            'validator.mapping.cache.prefix' => '',
            'validator.mapping.loader.loader_chain.class' => 'Symfony\\Component\\Validator\\Mapping\\Loader\\LoaderChain',
            'validator.mapping.loader.static_method_loader.class' => 'Symfony\\Component\\Validator\\Mapping\\Loader\\StaticMethodLoader',
            'validator.mapping.loader.annotation_loader.class' => 'Symfony\\Component\\Validator\\Mapping\\Loader\\AnnotationLoader',
            'validator.mapping.loader.xml_files_loader.class' => 'Symfony\\Component\\Validator\\Mapping\\Loader\\XmlFilesLoader',
            'validator.mapping.loader.yaml_files_loader.class' => 'Symfony\\Component\\Validator\\Mapping\\Loader\\YamlFilesLoader',
            'validator.validator_factory.class' => 'Symfony\\Bundle\\FrameworkBundle\\Validator\\ConstraintValidatorFactory',
            'validator.mapping.loader.xml_files_loader.mapping_files' => array(
                0 => 'C:\\xampp\\htdocs\\beta-buggl\\vendor\\symfony\\symfony\\src\\Symfony\\Component\\Form/Resources/config/validation.xml',
            ),
            'validator.mapping.loader.yaml_files_loader.mapping_files' => array(
                0 => 'C:\\xampp\\htdocs\\beta-buggl\\src\\Buggl\\MainBundle\\Resources\\config\\validation.yml',
            ),
            'router.class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\Router',
            'router.request_context.class' => 'Symfony\\Component\\Routing\\RequestContext',
            'routing.loader.class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\DelegatingLoader',
            'routing.resolver.class' => 'Symfony\\Component\\Config\\Loader\\LoaderResolver',
            'routing.loader.xml.class' => 'Symfony\\Component\\Routing\\Loader\\XmlFileLoader',
            'routing.loader.yml.class' => 'Symfony\\Component\\Routing\\Loader\\YamlFileLoader',
            'routing.loader.php.class' => 'Symfony\\Component\\Routing\\Loader\\PhpFileLoader',
            'router.options.generator_class' => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator',
            'router.options.generator_base_class' => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator',
            'router.options.generator_dumper_class' => 'Symfony\\Component\\Routing\\Generator\\Dumper\\PhpGeneratorDumper',
            'router.options.matcher_class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\RedirectableUrlMatcher',
            'router.options.matcher_base_class' => 'Symfony\\Bundle\\FrameworkBundle\\Routing\\RedirectableUrlMatcher',
            'router.options.matcher_dumper_class' => 'Symfony\\Component\\Routing\\Matcher\\Dumper\\PhpMatcherDumper',
            'router.cache_warmer.class' => 'Symfony\\Bundle\\FrameworkBundle\\CacheWarmer\\RouterCacheWarmer',
            'router.options.matcher.cache_class' => 'appProdUrlMatcher',
            'router.options.generator.cache_class' => 'appProdUrlGenerator',
            'router_listener.class' => 'Symfony\\Component\\HttpKernel\\EventListener\\RouterListener',
            'router.request_context.host' => 'localhost',
            'router.request_context.scheme' => 'http',
            'router.resource' => 'C:/xampp/htdocs/beta-buggl/app/cache/prod/assetic/routing.yml',
            'router.cache_class_prefix' => 'appProd',
            'request_listener.http_port' => 80,
            'request_listener.https_port' => 443,
            'templating.engine.delegating.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\DelegatingEngine',
            'templating.name_parser.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\TemplateNameParser',
            'templating.filename_parser.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\TemplateFilenameParser',
            'templating.cache_warmer.template_paths.class' => 'Symfony\\Bundle\\FrameworkBundle\\CacheWarmer\\TemplatePathsCacheWarmer',
            'templating.locator.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Loader\\TemplateLocator',
            'templating.loader.filesystem.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Loader\\FilesystemLoader',
            'templating.loader.cache.class' => 'Symfony\\Component\\Templating\\Loader\\CacheLoader',
            'templating.loader.chain.class' => 'Symfony\\Component\\Templating\\Loader\\ChainLoader',
            'templating.finder.class' => 'Symfony\\Bundle\\FrameworkBundle\\CacheWarmer\\TemplateFinder',
            'templating.engine.php.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\PhpEngine',
            'templating.helper.slots.class' => 'Symfony\\Component\\Templating\\Helper\\SlotsHelper',
            'templating.helper.assets.class' => 'Symfony\\Component\\Templating\\Helper\\CoreAssetsHelper',
            'templating.helper.actions.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Helper\\ActionsHelper',
            'templating.helper.router.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Helper\\RouterHelper',
            'templating.helper.request.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Helper\\RequestHelper',
            'templating.helper.session.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Helper\\SessionHelper',
            'templating.helper.code.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Helper\\CodeHelper',
            'templating.helper.translator.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Helper\\TranslatorHelper',
            'templating.helper.form.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Helper\\FormHelper',
            'templating.form.engine.class' => 'Symfony\\Component\\Form\\Extension\\Templating\\TemplatingRendererEngine',
            'templating.form.renderer.class' => 'Symfony\\Component\\Form\\FormRenderer',
            'templating.globals.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\GlobalVariables',
            'templating.asset.path_package.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Asset\\PathPackage',
            'templating.asset.url_package.class' => 'Symfony\\Component\\Templating\\Asset\\UrlPackage',
            'templating.asset.package_factory.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Asset\\PackageFactory',
            'templating.helper.code.file_link_format' => NULL,
            'templating.helper.form.resources' => array(
                0 => 'FrameworkBundle:Form',
            ),
            'templating.hinclude.default_template' => NULL,
            'templating.debugger.class' => 'Symfony\\Bundle\\FrameworkBundle\\Templating\\Debugger',
            'templating.loader.cache.path' => NULL,
            'templating.engines' => array(
                0 => 'twig',
            ),
            'annotations.reader.class' => 'Doctrine\\Common\\Annotations\\AnnotationReader',
            'annotations.cached_reader.class' => 'Doctrine\\Common\\Annotations\\CachedReader',
            'annotations.file_cache_reader.class' => 'Doctrine\\Common\\Annotations\\FileCacheReader',
            'security.context.class' => 'Symfony\\Component\\Security\\Core\\SecurityContext',
            'security.user_checker.class' => 'Symfony\\Component\\Security\\Core\\User\\UserChecker',
            'security.encoder_factory.generic.class' => 'Symfony\\Component\\Security\\Core\\Encoder\\EncoderFactory',
            'security.encoder.digest.class' => 'Symfony\\Component\\Security\\Core\\Encoder\\MessageDigestPasswordEncoder',
            'security.encoder.plain.class' => 'Symfony\\Component\\Security\\Core\\Encoder\\PlaintextPasswordEncoder',
            'security.user.provider.in_memory.class' => 'Symfony\\Component\\Security\\Core\\User\\InMemoryUserProvider',
            'security.user.provider.in_memory.user.class' => 'Symfony\\Component\\Security\\Core\\User\\User',
            'security.user.provider.chain.class' => 'Symfony\\Component\\Security\\Core\\User\\ChainUserProvider',
            'security.authentication.trust_resolver.class' => 'Symfony\\Component\\Security\\Core\\Authentication\\AuthenticationTrustResolver',
            'security.authentication.trust_resolver.anonymous_class' => 'Symfony\\Component\\Security\\Core\\Authentication\\Token\\AnonymousToken',
            'security.authentication.trust_resolver.rememberme_class' => 'Symfony\\Component\\Security\\Core\\Authentication\\Token\\RememberMeToken',
            'security.authentication.manager.class' => 'Symfony\\Component\\Security\\Core\\Authentication\\AuthenticationProviderManager',
            'security.authentication.session_strategy.class' => 'Symfony\\Component\\Security\\Http\\Session\\SessionAuthenticationStrategy',
            'security.access.decision_manager.class' => 'Symfony\\Component\\Security\\Core\\Authorization\\AccessDecisionManager',
            'security.access.simple_role_voter.class' => 'Symfony\\Component\\Security\\Core\\Authorization\\Voter\\RoleVoter',
            'security.access.authenticated_voter.class' => 'Symfony\\Component\\Security\\Core\\Authorization\\Voter\\AuthenticatedVoter',
            'security.access.role_hierarchy_voter.class' => 'Symfony\\Component\\Security\\Core\\Authorization\\Voter\\RoleHierarchyVoter',
            'security.firewall.class' => 'Symfony\\Component\\Security\\Http\\Firewall',
            'security.firewall.map.class' => 'Symfony\\Bundle\\SecurityBundle\\Security\\FirewallMap',
            'security.firewall.context.class' => 'Symfony\\Bundle\\SecurityBundle\\Security\\FirewallContext',
            'security.matcher.class' => 'Symfony\\Component\\HttpFoundation\\RequestMatcher',
            'security.role_hierarchy.class' => 'Symfony\\Component\\Security\\Core\\Role\\RoleHierarchy',
            'security.http_utils.class' => 'Symfony\\Component\\Security\\Http\\HttpUtils',
            'security.validator.user_password.class' => 'Symfony\\Component\\Security\\Core\\Validator\\Constraint\\UserPasswordValidator',
            'security.authentication.retry_entry_point.class' => 'Symfony\\Component\\Security\\Http\\EntryPoint\\RetryAuthenticationEntryPoint',
            'security.channel_listener.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\ChannelListener',
            'security.authentication.form_entry_point.class' => 'Symfony\\Component\\Security\\Http\\EntryPoint\\FormAuthenticationEntryPoint',
            'security.authentication.listener.form.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\UsernamePasswordFormAuthenticationListener',
            'security.authentication.listener.basic.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\BasicAuthenticationListener',
            'security.authentication.basic_entry_point.class' => 'Symfony\\Component\\Security\\Http\\EntryPoint\\BasicAuthenticationEntryPoint',
            'security.authentication.listener.digest.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\DigestAuthenticationListener',
            'security.authentication.digest_entry_point.class' => 'Symfony\\Component\\Security\\Http\\EntryPoint\\DigestAuthenticationEntryPoint',
            'security.authentication.listener.x509.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\X509AuthenticationListener',
            'security.authentication.listener.anonymous.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\AnonymousAuthenticationListener',
            'security.authentication.switchuser_listener.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\SwitchUserListener',
            'security.logout_listener.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\LogoutListener',
            'security.logout.handler.session.class' => 'Symfony\\Component\\Security\\Http\\Logout\\SessionLogoutHandler',
            'security.logout.handler.cookie_clearing.class' => 'Symfony\\Component\\Security\\Http\\Logout\\CookieClearingLogoutHandler',
            'security.logout.success_handler.class' => 'Symfony\\Component\\Security\\Http\\Logout\\DefaultLogoutSuccessHandler',
            'security.access_listener.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\AccessListener',
            'security.access_map.class' => 'Symfony\\Component\\Security\\Http\\AccessMap',
            'security.exception_listener.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\ExceptionListener',
            'security.context_listener.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\ContextListener',
            'security.authentication.provider.dao.class' => 'Symfony\\Component\\Security\\Core\\Authentication\\Provider\\DaoAuthenticationProvider',
            'security.authentication.provider.pre_authenticated.class' => 'Symfony\\Component\\Security\\Core\\Authentication\\Provider\\PreAuthenticatedAuthenticationProvider',
            'security.authentication.provider.anonymous.class' => 'Symfony\\Component\\Security\\Core\\Authentication\\Provider\\AnonymousAuthenticationProvider',
            'security.authentication.success_handler.class' => 'Symfony\\Component\\Security\\Http\\Authentication\\DefaultAuthenticationSuccessHandler',
            'security.authentication.failure_handler.class' => 'Symfony\\Component\\Security\\Http\\Authentication\\DefaultAuthenticationFailureHandler',
            'security.authentication.provider.rememberme.class' => 'Symfony\\Component\\Security\\Core\\Authentication\\Provider\\RememberMeAuthenticationProvider',
            'security.authentication.listener.rememberme.class' => 'Symfony\\Component\\Security\\Http\\Firewall\\RememberMeListener',
            'security.rememberme.token.provider.in_memory.class' => 'Symfony\\Component\\Security\\Core\\Authentication\\RememberMe\\InMemoryTokenProvider',
            'security.authentication.rememberme.services.persistent.class' => 'Symfony\\Component\\Security\\Http\\RememberMe\\PersistentTokenBasedRememberMeServices',
            'security.authentication.rememberme.services.simplehash.class' => 'Symfony\\Component\\Security\\Http\\RememberMe\\TokenBasedRememberMeServices',
            'security.rememberme.response_listener.class' => 'Symfony\\Component\\Security\\Http\\RememberMe\\ResponseListener',
            'templating.helper.logout_url.class' => 'Symfony\\Bundle\\SecurityBundle\\Templating\\Helper\\LogoutUrlHelper',
            'templating.helper.security.class' => 'Symfony\\Bundle\\SecurityBundle\\Templating\\Helper\\SecurityHelper',
            'twig.extension.logout_url.class' => 'Symfony\\Bundle\\SecurityBundle\\Twig\\Extension\\LogoutUrlExtension',
            'twig.extension.security.class' => 'Symfony\\Bridge\\Twig\\Extension\\SecurityExtension',
            'data_collector.security.class' => 'Symfony\\Bundle\\SecurityBundle\\DataCollector\\SecurityDataCollector',
            'security.access.denied_url' => NULL,
            'security.authentication.manager.erase_credentials' => true,
            'security.authentication.session_strategy.strategy' => 'migrate',
            'security.access.always_authenticate_before_granting' => false,
            'security.authentication.hide_user_not_found' => true,
            'security.role_hierarchy.roles' => array(

            ),
            'twig.class' => 'Twig_Environment',
            'twig.loader.class' => 'Symfony\\Bundle\\TwigBundle\\Loader\\FilesystemLoader',
            'templating.engine.twig.class' => 'Symfony\\Bundle\\TwigBundle\\TwigEngine',
            'twig.cache_warmer.class' => 'Symfony\\Bundle\\TwigBundle\\CacheWarmer\\TemplateCacheCacheWarmer',
            'twig.extension.trans.class' => 'Symfony\\Bridge\\Twig\\Extension\\TranslationExtension',
            'twig.extension.assets.class' => 'Symfony\\Bundle\\TwigBundle\\Extension\\AssetsExtension',
            'twig.extension.actions.class' => 'Symfony\\Bundle\\TwigBundle\\Extension\\ActionsExtension',
            'twig.extension.code.class' => 'Symfony\\Bundle\\TwigBundle\\Extension\\CodeExtension',
            'twig.extension.routing.class' => 'Symfony\\Bridge\\Twig\\Extension\\RoutingExtension',
            'twig.extension.yaml.class' => 'Symfony\\Bridge\\Twig\\Extension\\YamlExtension',
            'twig.extension.form.class' => 'Symfony\\Bridge\\Twig\\Extension\\FormExtension',
            'twig.form.engine.class' => 'Symfony\\Bridge\\Twig\\Form\\TwigRendererEngine',
            'twig.form.renderer.class' => 'Symfony\\Bridge\\Twig\\Form\\TwigRenderer',
            'twig.translation.extractor.class' => 'Symfony\\Bridge\\Twig\\Translation\\TwigExtractor',
            'twig.exception_listener.class' => 'Symfony\\Component\\HttpKernel\\EventListener\\ExceptionListener',
            'twig.exception_listener.controller' => 'Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController::showAction',
            'twig.form.resources' => array(
                0 => 'form_div_layout.html.twig',
            ),
            'twig.options' => array(
                'cache' => false,
                'debug' => true,
                'strict_variables' => true,
                'exception_controller' => 'Symfony\\Bundle\\TwigBundle\\Controller\\ExceptionController::showAction',
                'charset' => 'UTF-8',
                'paths' => array(

                ),
            ),
            'debug.templating.engine.twig.class' => 'Symfony\\Bundle\\TwigBundle\\Debug\\TimedTwigEngine',
            'monolog.logger.class' => 'Symfony\\Bridge\\Monolog\\Logger',
            'monolog.gelf.publisher.class' => 'Gelf\\MessagePublisher',
            'monolog.handler.stream.class' => 'Monolog\\Handler\\StreamHandler',
            'monolog.handler.group.class' => 'Monolog\\Handler\\GroupHandler',
            'monolog.handler.buffer.class' => 'Monolog\\Handler\\BufferHandler',
            'monolog.handler.rotating_file.class' => 'Monolog\\Handler\\RotatingFileHandler',
            'monolog.handler.syslog.class' => 'Monolog\\Handler\\SyslogHandler',
            'monolog.handler.null.class' => 'Monolog\\Handler\\NullHandler',
            'monolog.handler.test.class' => 'Monolog\\Handler\\TestHandler',
            'monolog.handler.gelf.class' => 'Monolog\\Handler\\GelfHandler',
            'monolog.handler.firephp.class' => 'Symfony\\Bridge\\Monolog\\Handler\\FirePHPHandler',
            'monolog.handler.chromephp.class' => 'Symfony\\Bridge\\Monolog\\Handler\\ChromePhpHandler',
            'monolog.handler.debug.class' => 'Symfony\\Bridge\\Monolog\\Handler\\DebugHandler',
            'monolog.handler.swift_mailer.class' => 'Monolog\\Handler\\SwiftMailerHandler',
            'monolog.handler.native_mailer.class' => 'Monolog\\Handler\\NativeMailerHandler',
            'monolog.handler.socket.class' => 'Monolog\\Handler\\SocketHandler',
            'monolog.handler.fingers_crossed.class' => 'Monolog\\Handler\\FingersCrossedHandler',
            'monolog.handler.fingers_crossed.error_level_activation_strategy.class' => 'Monolog\\Handler\\FingersCrossed\\ErrorLevelActivationStrategy',
            'monolog.handlers_to_channels' => array(
                'monolog.handler.main' => NULL,
            ),
            'swiftmailer.class' => 'Swift_Mailer',
            'swiftmailer.transport.sendmail.class' => 'Swift_Transport_SendmailTransport',
            'swiftmailer.transport.mail.class' => 'Swift_Transport_MailTransport',
            'swiftmailer.transport.failover.class' => 'Swift_Transport_FailoverTransport',
            'swiftmailer.plugin.redirecting.class' => 'Swift_Plugins_RedirectingPlugin',
            'swiftmailer.plugin.impersonate.class' => 'Swift_Plugins_ImpersonatePlugin',
            'swiftmailer.plugin.messagelogger.class' => 'Swift_Plugins_MessageLogger',
            'swiftmailer.plugin.antiflood.class' => 'Swift_Plugins_AntiFloodPlugin',
            'swiftmailer.plugin.antiflood.threshold' => 99,
            'swiftmailer.plugin.antiflood.sleep' => 0,
            'swiftmailer.data_collector.class' => 'Symfony\\Bridge\\Swiftmailer\\DataCollector\\MessageDataCollector',
            'swiftmailer.transport.smtp.class' => 'Swift_Transport_EsmtpTransport',
            'swiftmailer.transport.smtp.encryption' => 'ssl',
            'swiftmailer.transport.smtp.port' => 465,
            'swiftmailer.transport.smtp.host' => 'smtp.gmail.com',
            'swiftmailer.transport.smtp.username' => 'admin@buggl.com',
            'swiftmailer.transport.smtp.password' => 'sleeper16',
            'swiftmailer.transport.smtp.auth_mode' => 'login',
            'swiftmailer.transport.smtp.timeout' => 30,
            'swiftmailer.transport.smtp.source_ip' => NULL,
            'swiftmailer.plugin.blackhole.class' => 'Swift_Plugins_BlackholePlugin',
            'swiftmailer.spool.memory.class' => 'Swift_MemorySpool',
            'swiftmailer.email_sender.listener.class' => 'Symfony\\Bundle\\SwiftmailerBundle\\EventListener\\EmailSenderListener',
            'swiftmailer.spool.memory.path' => 'C:/xampp/htdocs/beta-buggl/app/cache/prod/swiftmailer/spool',
            'swiftmailer.spool.enabled' => true,
            'swiftmailer.sender_address' => NULL,
            'swiftmailer.single_address' => NULL,
            'swiftmailer.delivery_whitelist' => array(

            ),
            'assetic.asset_factory.class' => 'Symfony\\Bundle\\AsseticBundle\\Factory\\AssetFactory',
            'assetic.asset_manager.class' => 'Assetic\\Factory\\LazyAssetManager',
            'assetic.asset_manager_cache_warmer.class' => 'Symfony\\Bundle\\AsseticBundle\\CacheWarmer\\AssetManagerCacheWarmer',
            'assetic.cached_formula_loader.class' => 'Assetic\\Factory\\Loader\\CachedFormulaLoader',
            'assetic.config_cache.class' => 'Assetic\\Cache\\ConfigCache',
            'assetic.config_loader.class' => 'Symfony\\Bundle\\AsseticBundle\\Factory\\Loader\\ConfigurationLoader',
            'assetic.config_resource.class' => 'Symfony\\Bundle\\AsseticBundle\\Factory\\Resource\\ConfigurationResource',
            'assetic.coalescing_directory_resource.class' => 'Symfony\\Bundle\\AsseticBundle\\Factory\\Resource\\CoalescingDirectoryResource',
            'assetic.directory_resource.class' => 'Symfony\\Bundle\\AsseticBundle\\Factory\\Resource\\DirectoryResource',
            'assetic.filter_manager.class' => 'Symfony\\Bundle\\AsseticBundle\\FilterManager',
            'assetic.worker.ensure_filter.class' => 'Assetic\\Factory\\Worker\\EnsureFilterWorker',
            'assetic.worker.cache_busting.class' => 'Assetic\\Factory\\Worker\\CacheBustingWorker',
            'assetic.value_supplier.class' => 'Symfony\\Bundle\\AsseticBundle\\DefaultValueSupplier',
            'assetic.node.paths' => array(

            ),
            'assetic.cache_dir' => 'C:/xampp/htdocs/beta-buggl/app/cache/prod/assetic',
            'assetic.bundles' => array(
                0 => 'BugglMainBundle',
                1 => 'BugglPhotoBundle',
            ),
            'assetic.twig_extension.class' => 'Symfony\\Bundle\\AsseticBundle\\Twig\\AsseticExtension',
            'assetic.twig_formula_loader.class' => 'Assetic\\Extension\\Twig\\TwigFormulaLoader',
            'assetic.helper.dynamic.class' => 'Symfony\\Bundle\\AsseticBundle\\Templating\\DynamicAsseticHelper',
            'assetic.helper.static.class' => 'Symfony\\Bundle\\AsseticBundle\\Templating\\StaticAsseticHelper',
            'assetic.php_formula_loader.class' => 'Symfony\\Bundle\\AsseticBundle\\Factory\\Loader\\AsseticHelperFormulaLoader',
            'assetic.debug' => true,
            'assetic.use_controller' => true,
            'assetic.enable_profiler' => false,
            'assetic.read_from' => 'C:/xampp/htdocs/beta-buggl/app/../web',
            'assetic.write_to' => 'C:/xampp/htdocs/beta-buggl/app/../web',
            'assetic.variables' => array(

            ),
            'assetic.java.bin' => 'C:\\ProgramData\\Oracle\\Java\\javapath\\java.EXE',
            'assetic.node.bin' => '/usr/bin/node',
            'assetic.ruby.bin' => '/usr/bin/ruby',
            'assetic.sass.bin' => '/usr/bin/sass',
            'assetic.filter.cssrewrite.class' => 'Assetic\\Filter\\CssRewriteFilter',
            'assetic.filter.yui_css.class' => 'Assetic\\Filter\\Yui\\CssCompressorFilter',
            'assetic.filter.yui_css.java' => 'C:\\ProgramData\\Oracle\\Java\\javapath\\java.EXE',
            'assetic.filter.yui_css.jar' => 'C:/xampp/htdocs/beta-buggl/app/Resources/java/yuicompressor-2.4.7.jar',
            'assetic.filter.yui_css.charset' => 'UTF-8',
            'assetic.filter.yui_css.stacksize' => NULL,
            'assetic.filter.yui_css.timeout' => NULL,
            'assetic.filter.yui_css.linebreak' => NULL,
            'assetic.filter.yui_js.class' => 'Assetic\\Filter\\Yui\\JsCompressorFilter',
            'assetic.filter.yui_js.java' => 'C:\\ProgramData\\Oracle\\Java\\javapath\\java.EXE',
            'assetic.filter.yui_js.jar' => 'C:/xampp/htdocs/beta-buggl/app/Resources/java/yuicompressor-2.4.7.jar',
            'assetic.filter.yui_js.charset' => 'UTF-8',
            'assetic.filter.yui_js.stacksize' => NULL,
            'assetic.filter.yui_js.timeout' => NULL,
            'assetic.filter.yui_js.nomunge' => NULL,
            'assetic.filter.yui_js.preserve_semi' => NULL,
            'assetic.filter.yui_js.disable_optimizations' => NULL,
            'assetic.filter.yui_js.linebreak' => NULL,
            'assetic.twig_extension.functions' => array(

            ),
            'assetic.controller.class' => 'Symfony\\Bundle\\AsseticBundle\\Controller\\AsseticController',
            'assetic.routing_loader.class' => 'Symfony\\Bundle\\AsseticBundle\\Routing\\AsseticLoader',
            'assetic.cache.class' => 'Assetic\\Cache\\FilesystemCache',
            'assetic.use_controller_worker.class' => 'Symfony\\Bundle\\AsseticBundle\\Factory\\Worker\\UseControllerWorker',
            'assetic.request_listener.class' => 'Symfony\\Bundle\\AsseticBundle\\EventListener\\RequestListener',
            'doctrine.dbal.logger.chain.class' => 'Doctrine\\DBAL\\Logging\\LoggerChain',
            'doctrine.dbal.logger.profiling.class' => 'Doctrine\\DBAL\\Logging\\DebugStack',
            'doctrine.dbal.logger.class' => 'Symfony\\Bridge\\Doctrine\\Logger\\DbalLogger',
            'doctrine.dbal.configuration.class' => 'Doctrine\\DBAL\\Configuration',
            'doctrine.data_collector.class' => 'Symfony\\Bridge\\Doctrine\\DataCollector\\DoctrineDataCollector',
            'doctrine.dbal.connection.event_manager.class' => 'Symfony\\Bridge\\Doctrine\\ContainerAwareEventManager',
            'doctrine.dbal.connection_factory.class' => 'Doctrine\\Bundle\\DoctrineBundle\\ConnectionFactory',
            'doctrine.dbal.events.mysql_session_init.class' => 'Doctrine\\DBAL\\Event\\Listeners\\MysqlSessionInit',
            'doctrine.dbal.events.oracle_session_init.class' => 'Doctrine\\DBAL\\Event\\Listeners\\OracleSessionInit',
            'doctrine.class' => 'Doctrine\\Bundle\\DoctrineBundle\\Registry',
            'doctrine.entity_managers' => array(
                'default' => 'doctrine.orm.default_entity_manager',
            ),
            'doctrine.default_entity_manager' => 'default',
            'doctrine.dbal.connection_factory.types' => array(

            ),
            'doctrine.connections' => array(
                'default' => 'doctrine.dbal.default_connection',
            ),
            'doctrine.default_connection' => 'default',
            'doctrine.orm.configuration.class' => 'Doctrine\\ORM\\Configuration',
            'doctrine.orm.entity_manager.class' => 'Doctrine\\ORM\\EntityManager',
            'doctrine.orm.manager_configurator.class' => 'Doctrine\\Bundle\\DoctrineBundle\\ManagerConfigurator',
            'doctrine.orm.cache.array.class' => 'Doctrine\\Common\\Cache\\ArrayCache',
            'doctrine.orm.cache.apc.class' => 'Doctrine\\Common\\Cache\\ApcCache',
            'doctrine.orm.cache.memcache.class' => 'Doctrine\\Common\\Cache\\MemcacheCache',
            'doctrine.orm.cache.memcache_host' => 'localhost',
            'doctrine.orm.cache.memcache_port' => 11211,
            'doctrine.orm.cache.memcache_instance.class' => 'Memcache',
            'doctrine.orm.cache.memcached.class' => 'Doctrine\\Common\\Cache\\MemcachedCache',
            'doctrine.orm.cache.memcached_host' => 'localhost',
            'doctrine.orm.cache.memcached_port' => 11211,
            'doctrine.orm.cache.memcached_instance.class' => 'Memcached',
            'doctrine.orm.cache.redis.class' => 'Doctrine\\Common\\Cache\\RedisCache',
            'doctrine.orm.cache.redis_host' => 'localhost',
            'doctrine.orm.cache.redis_port' => 6379,
            'doctrine.orm.cache.redis_instance.class' => 'Redis',
            'doctrine.orm.cache.xcache.class' => 'Doctrine\\Common\\Cache\\XcacheCache',
            'doctrine.orm.cache.wincache.class' => 'Doctrine\\Common\\Cache\\WinCacheCache',
            'doctrine.orm.cache.zenddata.class' => 'Doctrine\\Common\\Cache\\ZendDataCache',
            'doctrine.orm.metadata.driver_chain.class' => 'Doctrine\\ORM\\Mapping\\Driver\\DriverChain',
            'doctrine.orm.metadata.annotation.class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
            'doctrine.orm.metadata.xml.class' => 'Doctrine\\ORM\\Mapping\\Driver\\SimplifiedXmlDriver',
            'doctrine.orm.metadata.yml.class' => 'Doctrine\\ORM\\Mapping\\Driver\\SimplifiedYamlDriver',
            'doctrine.orm.metadata.php.class' => 'Doctrine\\ORM\\Mapping\\Driver\\PHPDriver',
            'doctrine.orm.metadata.staticphp.class' => 'Doctrine\\ORM\\Mapping\\Driver\\StaticPHPDriver',
            'doctrine.orm.proxy_cache_warmer.class' => 'Symfony\\Bridge\\Doctrine\\CacheWarmer\\ProxyCacheWarmer',
            'form.type_guesser.doctrine.class' => 'Symfony\\Bridge\\Doctrine\\Form\\DoctrineOrmTypeGuesser',
            'doctrine.orm.validator.unique.class' => 'Symfony\\Bridge\\Doctrine\\Validator\\Constraints\\UniqueEntityValidator',
            'doctrine.orm.validator_initializer.class' => 'Symfony\\Bridge\\Doctrine\\Validator\\DoctrineInitializer',
            'doctrine.orm.security.user.provider.class' => 'Symfony\\Bridge\\Doctrine\\Security\\User\\EntityUserProvider',
            'doctrine.orm.listeners.resolve_target_entity.class' => 'Doctrine\\ORM\\Tools\\ResolveTargetEntityListener',
            'doctrine.orm.naming_strategy.default.class' => 'Doctrine\\ORM\\Mapping\\DefaultNamingStrategy',
            'doctrine.orm.naming_strategy.underscore.class' => 'Doctrine\\ORM\\Mapping\\UnderscoreNamingStrategy',
            'doctrine.orm.auto_generate_proxy_classes' => true,
            'doctrine.orm.proxy_dir' => 'C:/xampp/htdocs/beta-buggl/app/cache/prod/doctrine/orm/Proxies',
            'doctrine.orm.proxy_namespace' => 'Proxies',
            'sensio_framework_extra.view.guesser.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\Templating\\TemplateGuesser',
            'sensio_framework_extra.controller.listener.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\EventListener\\ControllerListener',
            'sensio_framework_extra.routing.loader.annot_dir.class' => 'Symfony\\Component\\Routing\\Loader\\AnnotationDirectoryLoader',
            'sensio_framework_extra.routing.loader.annot_file.class' => 'Symfony\\Component\\Routing\\Loader\\AnnotationFileLoader',
            'sensio_framework_extra.routing.loader.annot_class.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\Routing\\AnnotatedRouteControllerLoader',
            'sensio_framework_extra.converter.listener.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\EventListener\\ParamConverterListener',
            'sensio_framework_extra.converter.manager.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\Request\\ParamConverter\\ParamConverterManager',
            'sensio_framework_extra.converter.doctrine.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\Request\\ParamConverter\\DoctrineParamConverter',
            'sensio_framework_extra.converter.datetime.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\Request\\ParamConverter\\DateTimeParamConverter',
            'sensio_framework_extra.view.listener.class' => 'Sensio\\Bundle\\FrameworkExtraBundle\\EventListener\\TemplateListener',
            'jms_aop.cache_dir' => 'C:/xampp/htdocs/beta-buggl/app/cache/prod/jms_aop',
            'jms_aop.interceptor_loader.class' => 'JMS\\AopBundle\\Aop\\InterceptorLoader',
            'jms_di_extra.metadata.driver.annotation_driver.class' => 'JMS\\DiExtraBundle\\Metadata\\Driver\\AnnotationDriver',
            'jms_di_extra.metadata.driver.configured_controller_injections.class' => 'JMS\\DiExtraBundle\\Metadata\\Driver\\ConfiguredControllerInjectionsDriver',
            'jms_di_extra.metadata.driver.lazy_loading_driver.class' => 'Metadata\\Driver\\LazyLoadingDriver',
            'jms_di_extra.metadata.metadata_factory.class' => 'Metadata\\MetadataFactory',
            'jms_di_extra.metadata.cache.file_cache.class' => 'Metadata\\Cache\\FileCache',
            'jms_di_extra.metadata.converter.class' => 'JMS\\DiExtraBundle\\Metadata\\MetadataConverter',
            'jms_di_extra.controller_resolver.class' => 'JMS\\DiExtraBundle\\HttpKernel\\ControllerResolver',
            'jms_di_extra.controller_injectors_warmer.class' => 'JMS\\DiExtraBundle\\HttpKernel\\ControllerInjectorsWarmer',
            'jms_di_extra.all_bundles' => false,
            'jms_di_extra.bundles' => array(

            ),
            'jms_di_extra.directories' => array(

            ),
            'jms_di_extra.cache_dir' => 'C:/xampp/htdocs/beta-buggl/app/cache/prod/jms_diextra',
            'jms_di_extra.disable_grep' => false,
            'jms_di_extra.doctrine_integration' => true,
            'jms_di_extra.cache_warmer.controller_file_blacklist' => array(

            ),
            'jms_di_extra.doctrine_integration.entity_manager.file' => 'C:/xampp/htdocs/beta-buggl/app/cache/prod/jms_diextra/doctrine/EntityManager_566ea66f2289e.php',
            'jms_di_extra.doctrine_integration.entity_manager.class' => 'EntityManager566ea66f2289e_546a8d27f194334ee012bfe64f629947b07e4919\\__CG__\\Doctrine\\ORM\\EntityManager',
            'security.secured_services' => array(

            ),
            'security.access.method_interceptor.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\Interception\\MethodSecurityInterceptor',
            'security.access.method_access_control' => array(

            ),
            'security.access.remembering_access_decision_manager.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\RememberingAccessDecisionManager',
            'security.access.run_as_manager.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\RunAsManager',
            'security.authentication.provider.run_as.class' => 'JMS\\SecurityExtraBundle\\Security\\Authentication\\Provider\\RunAsAuthenticationProvider',
            'security.run_as.key' => 'RunAsToken',
            'security.run_as.role_prefix' => 'ROLE_',
            'security.access.after_invocation_manager.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\AfterInvocation\\AfterInvocationManager',
            'security.access.after_invocation.acl_provider.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\AfterInvocation\\AclAfterInvocationProvider',
            'security.access.iddqd_voter.class' => 'JMS\\SecurityExtraBundle\\Security\\Authorization\\Voter\\IddqdVoter',
            'security.extra.metadata_factory.class' => 'Metadata\\MetadataFactory',
            'security.extra.lazy_loading_driver.class' => 'Metadata\\Driver\\LazyLoadingDriver',
            'security.extra.driver_chain.class' => 'Metadata\\Driver\\DriverChain',
            'security.extra.annotation_driver.class' => 'JMS\\SecurityExtraBundle\\Metadata\\Driver\\AnnotationDriver',
            'security.extra.file_cache.class' => 'Metadata\\Cache\\FileCache',
            'security.access.secure_all_services' => false,
            'security.extra.cache_dir' => 'C:/xampp/htdocs/beta-buggl/app/cache/prod/jms_security',
            'security.authenticated_voter.disabled' => false,
            'security.role_voter.disabled' => false,
            'security.acl_voter.disabled' => false,
            'security.extra.iddqd_ignore_roles' => array(
                0 => 'ROLE_PREVIOUS_ADMIN',
            ),
            'security.iddqd_aliases' => array(

            ),
        );
    }
}

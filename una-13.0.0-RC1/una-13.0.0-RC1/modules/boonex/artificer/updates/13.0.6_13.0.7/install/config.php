<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Artificer',
    'version_from' => '13.0.6',
    'version_to' => '13.0.7',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-RC1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/artificer/updates/update_13.0.6_13.0.7/',
    'home_uri' => 'artificer_update_1306_1307',

    'module_dir' => 'boonex/artificer/',
    'module_uri' => 'artificer',

    'db_prefix' => 'bx_artificer_',
    'class_prefix' => 'BxArtificer',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 0,
        'update_files' => 1,
        'update_languages' => 0,
        'clear_db_cache' => 0,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Boonex Artificer Template',

    /**
     * Files Section
     */
    'delete_files' => array(
        'data/template/bx_forum/form_ghost_template.html',
        'data/template/bx_forum/form_ghost_template_cover.html',
        'data/template/bx_forum/form_ghost_template_file.html',
        'data/template/bx_forum/form_ghost_template_video.html',
        'data/template/system/menu_interactive_vertical.html',
        'data/template/bx_messenger/css/main.css',
        'data/template/bx_messenger/css/',
        'data/template/bx_messenger/',
        'data/template/bx_posts/unit_gallery.html',
        'data/template/bx_posts/', 
        'data/template/system/scripts/BxTemplAccountForms.php',
        'data/template/system/scripts/BxTemplAcl.php',
        'data/template/system/scripts/BxTemplAclServices.php',
        'data/template/system/scripts/BxTemplAuditGrid.php',
        'data/template/system/scripts/BxTemplAuditServices.php',
        'data/template/system/scripts/BxTemplCalendar.php',
        'data/template/system/scripts/BxTemplCaptchaHCaptcha.php',
        'data/template/system/scripts/BxTemplCaptchaReCAPTCHAInvisible.php',
        'data/template/system/scripts/BxTemplCaptchaReCAPTCHANew.php',
        'data/template/system/scripts/BxTemplCategory.php',
        'data/template/system/scripts/BxTemplChartServices.php',
        'data/template/system/scripts/BxTemplCmts.php',
        'data/template/system/scripts/BxTemplCmtsForm.php',
        'data/template/system/scripts/BxTemplCmtsGridAdministration.php',
        'data/template/system/scripts/BxTemplCmtsMenuActions.php',
        'data/template/system/scripts/BxTemplCmtsMenuManage.php',
        'data/template/system/scripts/BxTemplCmtsNotes.php',
        'data/template/system/scripts/BxTemplCmtsPageView.php',
        'data/template/system/scripts/BxTemplCmtsReviews.php',
        'data/template/system/scripts/BxTemplCmtsReviewsForm.php',
        'data/template/system/scripts/BxTemplCmtsServices.php',
        'data/template/system/scripts/BxTemplCmtsUploaderHTML5.php',
        'data/template/system/scripts/BxTemplCmtsUploaderSimple.php',
        'data/template/system/scripts/BxTemplConnection.php',
        'data/template/system/scripts/BxTemplCover.php',
        'data/template/system/scripts/BxTemplDashboardServices.php',
        'data/template/system/scripts/BxTemplEditorQuill.php',
        'data/template/system/scripts/BxTemplEmbedEmbedly.php',
        'data/template/system/scripts/BxTemplEmbedIframely.php',
        'data/template/system/scripts/BxTemplEmbedOembed.php',
        'data/template/system/scripts/BxTemplFavorite.php',
        'data/template/system/scripts/BxTemplFeature.php',
        'data/template/system/scripts/BxTemplFileHandlerCodeViewer.php',
        'data/template/system/scripts/BxTemplFileHandlerGoogleViewer.php',
        'data/template/system/scripts/BxTemplFileHandlerImagesViewer.php',
        'data/template/system/scripts/BxTemplFileHandlerMsViewer.php',
        'data/template/system/scripts/BxTemplFormAccount.php',
        'data/template/system/scripts/BxTemplFormConfirmEmail.php',
        'data/template/system/scripts/BxTemplFormConfirmPhone.php',
        'data/template/system/scripts/BxTemplFormForgotPassword.php',
        'data/template/system/scripts/BxTemplFormLogin.php',
        'data/template/system/scripts/BxTemplFormProfile.php',
        'data/template/system/scripts/BxTemplFormWiki.php',
        'data/template/system/scripts/BxTemplGrid.php',
        'data/template/system/scripts/BxTemplImageServices.php',
        'data/template/system/scripts/BxTemplInformer.php',
        'data/template/system/scripts/BxTemplLabel.php',
        'data/template/system/scripts/BxTemplLabelForm.php',
        'data/template/system/scripts/BxTemplLanguagesServices.php',
        'data/template/system/scripts/BxTemplLiveUpdates.php',
        'data/template/system/scripts/BxTemplLiveUpdatesServices.php',
        'data/template/system/scripts/BxTemplMenuAccountDashboard.php',
        'data/template/system/scripts/BxTemplMenuAccountPopup.php',
        'data/template/system/scripts/BxTemplMenuAddRelation.php',
        'data/template/system/scripts/BxTemplMenuCreatePost.php',
        'data/template/system/scripts/BxTemplMenuDashboardContentManage.php',
        'data/template/system/scripts/BxTemplMenuDashboardManageTools.php',
        'data/template/system/scripts/BxTemplMenuDashboardReportsManage.php',
        'data/template/system/scripts/BxTemplMenuFooter.php',
        'data/template/system/scripts/BxTemplMenuHomepage.php',
        'data/template/system/scripts/BxTemplMenuInteractive.php',
        'data/template/system/scripts/BxTemplMenuMoreAuto.php',
        'data/template/system/scripts/BxTemplMenuPanel.php',
        'data/template/system/scripts/BxTemplMenuProfileAdd.php',
        'data/template/system/scripts/BxTemplMenuProfileFollowings.php',
        'data/template/system/scripts/BxTemplMenuProfileStats.php',
        'data/template/system/scripts/BxTemplMenuSetAclLevel.php',
        'data/template/system/scripts/BxTemplMenuSetBadges.php',
        'data/template/system/scripts/BxTemplMenuSocialSharing.php',
        'data/template/system/scripts/BxTemplMenuSubmenu.php',
        'data/template/system/scripts/BxTemplMenuSwitchLanguage.php',
        'data/template/system/scripts/BxTemplMenuSwitchTemplate.php',
        'data/template/system/scripts/BxTemplMenuUnitMeta.php',
        'data/template/system/scripts/BxTemplMenuWiki.php',
        'data/template/system/scripts/BxTemplMetatags.php',
        'data/template/system/scripts/BxTemplPageAccount.php',
        'data/template/system/scripts/BxTemplPageDashboard.php',
        'data/template/system/scripts/BxTemplPagesSearchResult.php',
        'data/template/system/scripts/BxTemplPageWiki.php',
        'data/template/system/scripts/BxTemplPaginate.php',
        'data/template/system/scripts/BxTemplPaymentsServices.php',
        'data/template/system/scripts/BxTemplPlayerHtml5.php',
        'data/template/system/scripts/BxTemplPrivacy.php',
        'data/template/system/scripts/BxTemplPrivacyFormGroupCustom.php',
        'data/template/system/scripts/BxTemplReport.php',
        'data/template/system/scripts/BxTemplReportsGrid.php',
        'data/template/system/scripts/BxTemplRss.php',
        'data/template/system/scripts/BxTemplScore.php',
        'data/template/system/scripts/BxTemplSearchExtended.php',
        'data/template/system/scripts/BxTemplSearchExtendedCmts.php',
        'data/template/system/scripts/BxTemplSearchExtendedForm.php',
        'data/template/system/scripts/BxTemplSearchExtendedFormCmts.php',
        'data/template/system/scripts/BxTemplSearchExtendedServices.php',
        'data/template/system/scripts/BxTemplServiceAccount.php',
        'data/template/system/scripts/BxTemplServiceCategory.php',
        'data/template/system/scripts/BxTemplServiceConnections.php',
        'data/template/system/scripts/BxTemplServiceLogin.php',
        'data/template/system/scripts/BxTemplServiceMetatags.php',
        'data/template/system/scripts/BxTemplServiceMobileApps.php',
        'data/template/system/scripts/BxTemplServices.php',
        'data/template/system/scripts/BxTemplServiceWiki.php',
        'data/template/system/scripts/BxTemplStudioBadges.php',
        'data/template/system/scripts/BxTemplTemplateServices.php',
        'data/template/system/scripts/BxTemplUploaderCrop.php',
        'data/template/system/scripts/BxTemplUploaderHTML5.php',
        'data/template/system/scripts/BxTemplUploaderRecordVideo.php',
        'data/template/system/scripts/BxTemplUploaderSimple.php',
        'data/template/system/scripts/BxTemplView.php',
        'data/template/system/scripts/BxTemplVote.php',
        'data/template/system/scripts/BxTemplVoteLikes.php',
        'data/template/system/scripts/BxTemplVoteReactions.php',
        'data/template/system/scripts/BxTemplVoteReactionsMenuDo.php',
        'data/template/system/scripts/BxTemplVoteStars.php',
        'data/template/system/menu_interactive_vertical.html',
        'js/jquery.menu-aim.js',
    ),
);

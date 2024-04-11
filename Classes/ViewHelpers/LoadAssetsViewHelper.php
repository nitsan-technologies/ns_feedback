<?php

namespace NITSAN\NsFeedback\ViewHelpers;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class LoadAssetsViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;
    protected $extPath;
    protected $config = [];
    protected $constant;

    /**
     * @return void
     */
    public function render(): void
    {
        $settings = $this->templateVariableContainer->get('settings');
        $this->templateVariableContainer->get('cData');
        // Create pageRender instance.
        $pageRender = GeneralUtility::makeInstance(PageRenderer::class);
        $this->loadResource($pageRender, $settings);
    }

    /**
     * Load static CSS for the Forms
     * @param $pageRender
     * @param $settings
     */
    public function loadResource($pageRender, $settings): void
    {
        $css = '
        .nsbtn.btn-' . $settings['buttonstyle'] . ' {
            background-color:' . $settings['buttonbg'] . ';
            color: ' . $settings['buttoncolor'] . ';
            box-shadow: none;
            cursor: pointer;
            font-weight: ' . $settings['fontstyle'] . ';
            min-width: 105px;
            padding-top: 8px;
            width: auto !important;
            outline: medium none;
            -webkit-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
            border:1px solid ' . $settings['buttonbg'] . '
        }
        .send-msg span {
            color: ' . $settings['fontcolor'] . ';
            font-weight: ' . $settings['fontstyle'] . ';
        }
        ';
        $pageRender->addCssInlineBlock('globalSettingsCSS', $css);
    }
}

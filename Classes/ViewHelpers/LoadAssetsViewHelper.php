<?php

namespace NITSAN\NsFeedback\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class LoadAssetsViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;
    protected $extPath;
    protected $config = [];
    protected $constant;

    public function render()
    {

        // Collect the settings.
        $settings = $this->templateVariableContainer->get('settings');
        $cData = $this->templateVariableContainer->get('cData');
        // Create pageRender instance.
        $pageRender = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
        $this->loadResource($pageRender, $settings, $cData);
    }

    /**
     * Load static CSS for the Forms
     * @param $pageRender
     * @param $settings
     * @param $data
     */
    public function loadResource($pageRender, $settings, $data)
    {
        $css = '';

        $css .= '
        .nsbtn.btn-' . ($settings['buttonstyle'] ?? 'default') . ' {
            background-color:' . ($settings['buttonbg'] ?? '#000000') . ';
            color: ' . ($settings['buttoncolor'] ?? '#ffffff') . ';
            box-shadow: none;
            cursor: pointer;
            font-weight: ' . ($settings['fontstyle'] ?? 'normal') . ';
            min-width: 105px;
            padding-top: 8px;
            width: auto !important;
            outline: medium none;
            -webkit-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
            border:1px solid ' . ($settings['buttonbg'] ?? '#000000') . '
        }
        .send-msg span {
            color: ' . ($settings['fontcolor'] ?? '#000000') . ';
            font-weight: ' . ($settings['fontstyle'] ?? 'normal') . ';
        }
        ';
        $pageRender->addCssInlineBlock('globalSettingsCSS', $css);
    }
}

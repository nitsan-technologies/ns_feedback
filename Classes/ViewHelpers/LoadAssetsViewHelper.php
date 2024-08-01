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
        // Define default values if keys are not set
        $buttonStyle = $settings['buttonstyle'] ?? 'default-style';
        $buttonBg = $settings['buttonbg'] ?? '#ffffff';
        $buttonColor = $settings['buttoncolor'] ?? '#000000';
        $fontStyle = $settings['fontstyle'] ?? 'normal';
        $fontColor = $settings['fontcolor'] ?? '#000000';
    
        // Create CSS string
        $css = '
        .nsbtn.btn-' . htmlspecialchars($buttonStyle) . ' {
            background-color:' . htmlspecialchars($buttonBg) . ';
            color: ' . htmlspecialchars($buttonColor) . ';
            box-shadow: none;
            cursor: pointer;
            font-weight: ' . htmlspecialchars($fontStyle) . ';
            min-width: 105px;
            padding-top: 8px;
            width: auto !important;
            outline: medium none;
            -webkit-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
            border:1px solid ' . htmlspecialchars($buttonBg) . '
        }
        .send-msg span {
            color: ' . htmlspecialchars($fontColor) . ';
            font-weight: ' . htmlspecialchars($fontStyle) . ';
        }
        ';
    
        // Add CSS inline block
        $pageRender->addCssInlineBlock('globalSettingsCSS', $css);
    }
    
}

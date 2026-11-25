<?php

namespace NITSAN\NsFeedback\ViewHelpers;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class LoadAssetsViewHelper extends AbstractViewHelper
{
    protected $extPath;
    protected $config = [];
    protected $constant;

    public function render()
    {
        $settings = $this->templateVariableContainer->get('settings') ?? [];
        $cData = $this->templateVariableContainer->get('cData');
        $pageRender = GeneralUtility::makeInstance(PageRenderer::class);
        $this->loadResource($pageRender, is_array($settings) ? $settings : [], $cData);
    }

    /**
     * Load static CSS for the Forms
     *
     * @param PageRenderer $pageRender
     * @param array $settings
     * @param mixed $data
     */
    public function loadResource($pageRender, $settings, $data)
    {
        $buttonStyle = $this->sanitizeCssIdentifier((string)($settings['buttonstyle'] ?? 'default'), 'default');
        $buttonBg = $this->sanitizeCssColor((string)($settings['buttonbg'] ?? '#000000'), '#000000');
        $buttonColor = $this->sanitizeCssColor((string)($settings['buttoncolor'] ?? '#ffffff'), '#ffffff');
        $fontStyle = $this->sanitizeCssFontWeight((string)($settings['fontstyle'] ?? 'normal'), 'normal');
        $fontColor = $this->sanitizeCssColor((string)($settings['fontcolor'] ?? '#000000'), '#000000');

        $css = '
        .nsbtn.btn-' . $buttonStyle . ' {
            background-color:' . $buttonBg . ';
            color: ' . $buttonColor . ';
            box-shadow: none;
            cursor: pointer;
            font-weight: ' . $fontStyle . ';
            min-width: 105px;
            padding-top: 8px;
            width: auto !important;
            outline: medium none;
            -webkit-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
            border:1px solid ' . $buttonBg . '
        }
        .send-msg span {
            color: ' . $fontColor . ';
            font-weight: ' . $fontStyle . ';
        }
        ';
        $pageRender->addCssInlineBlock('globalSettingsCSS', $css);
    }

    protected function sanitizeCssColor(string $value, string $default): string
    {
        $value = trim($value);
        if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value) === 1) {
            return $value;
        }
        if (preg_match('/^rgba?\(\s*[\d.]+\s*(,\s*[\d.]+\s*){2,3}\)$/', $value) === 1) {
            return $value;
        }
        $named = [
            'transparent', 'inherit', 'currentcolor', 'black', 'white', 'red', 'green', 'blue',
            'gray', 'grey', 'orange', 'yellow', 'purple', 'navy', 'teal', 'silver',
        ];
        if (in_array(strtolower($value), $named, true)) {
            return strtolower($value);
        }
        return $default;
    }

    protected function sanitizeCssFontWeight(string $value, string $default): string
    {
        $value = trim(strtolower($value));
        $allowed = [
            'normal', 'bold', 'bolder', 'lighter',
            '100', '200', '300', '400', '500', '600', '700', '800', '900',
        ];
        return in_array($value, $allowed, true) ? $value : $default;
    }

    protected function sanitizeCssIdentifier(string $value, string $default): string
    {
        $value = trim($value);
        if (preg_match('/^[a-zA-Z0-9_-]+$/', $value) === 1) {
            return $value;
        }
        return $default;
    }
}

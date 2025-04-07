<?php
/**
 * 在网站底部显示页面加载时间
 * 
 * @package LoadTime
 * @author Cyruss-X
 * @version 1.0.0
 * @date 2025-04-07
 * @link https://github.com/Cyruss-X
 */
class LoadTime_Plugin implements Typecho_Plugin_Interface
{
    // 插件版本
    const VERSION = '1.0.0';
    
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->header = array('LoadTime_Plugin', 'renderHeader');
        Typecho_Plugin::factory('Widget_Archive')->footer = array('LoadTime_Plugin', 'renderFooter');
        return _t('插件已启用，页面将显示加载时间');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate()
    {
        return _t('插件已禁用，页面将不再显示加载时间');
    }
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        // 添加自定义CSS
        echo '<style>
            .loadtime-form-group {
                margin-bottom: 15px;
            }
            .loadtime-form-group label {
                display: block;
                margin-bottom: 5px;
                color: #495057;
                font-weight: 500;
            }
            .loadtime-form-group input[type="text"],
            .loadtime-form-group input[type="number"],
            .loadtime-form-group select,
            .loadtime-form-group textarea {
                width: 100%;
                padding: 8px 12px;
                border: 1px solid #ced4da;
                border-radius: 4px;
                font-size: 14px;
                transition: border-color 0.15s ease-in-out;
            }
            .loadtime-form-group input[type="text"]:focus,
            .loadtime-form-group input[type="number"]:focus,
            .loadtime-form-group select:focus,
            .loadtime-form-group textarea:focus {
                border-color: #80bdff;
                outline: 0;
                box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
            }
            .loadtime-radio-group {
                display: flex;
                gap: 20px;
                margin-bottom: 10px;
            }
            .loadtime-radio-group label {
                display: flex;
                align-items: center;
                gap: 5px;
                cursor: pointer;
            }
            .loadtime-radio-group input[type="radio"] {
                margin: 0;
            }
            .loadtime-version {
                text-align: center;
                margin-top: 30px;
                padding: 15px;
                background: #e9ecef;
                border-radius: 4px;
                color: #6c757d;
                font-size: 14px;
            }
            .loadtime-version a {
                color: #007bff;
                text-decoration: none;
            }
            .loadtime-version a:hover {
                text-decoration: underline;
            }
        </style>';

        // 位置设置
        $position = new Typecho_Widget_Helper_Form_Element_Radio(
            'position',
            array(
                'footer' => _t('页脚'),
                'header' => _t('页头'),
                'both' => _t('页头和页脚都显示')
            ),
            'footer',
            _t('显示位置'),
            _t('选择页面加载时间的显示位置')
        );
        $form->addInput($position);
        
        // 排除页面
        $excludePages = new Typecho_Widget_Helper_Form_Element_Text(
            'excludePages', null, '',
            _t('排除页面'),
            _t('设置不显示页面加载时间的页面，多个页面用英文逗号分隔，例如：archive,category,search')
        );
        $form->addInput($excludePages);

        // 显示方式
        $displayMode = new Typecho_Widget_Helper_Form_Element_Radio(
            'displayMode',
            array(
                'always' => _t('始终显示'),
                'hover' => _t('鼠标悬停时显示'),
                'click' => _t('点击后显示')
            ),
            'always',
            _t('显示方式'),
            _t('选择页面加载时间的显示方式')
        );
        $form->addInput($displayMode);
        
        // 显示文本设置
        $prefix = new Typecho_Widget_Helper_Form_Element_Text(
            'prefix', null, '页面加载时间: ',
            _t('前缀文本'), _t('显示在加载时间前面的文字，默认为"页面加载时间: "')
        );
        $form->addInput($prefix);
        
        $suffix = new Typecho_Widget_Helper_Form_Element_Text(
            'suffix', null, ' ms',
            _t('后缀文本'), _t('显示在加载时间后面的文字，默认为" ms"')
        );
        $form->addInput($suffix);
        
        // 分隔符设置
        $separator = new Typecho_Widget_Helper_Form_Element_Text(
            'separator', null, ' | ',
            _t('分隔符'), _t('各项信息之间的分隔符，默认为" | "')
        );
        $form->addInput($separator);
        
        // 时间单位设置
        $timeUnit = new Typecho_Widget_Helper_Form_Element_Radio(
            'timeUnit',
            array(
                'second' => _t('秒 (s)'),
                'millisecond' => _t('毫秒 (ms)')
            ),
            'second',
            _t('时间单位'),
            _t('选择页面加载时间的显示单位，秒或毫秒')
        );
        $form->addInput($timeUnit);
        
        // 精确度设置
        $precision = new Typecho_Widget_Helper_Form_Element_Select(
            'precision',
            array(
                '0' => _t('整数'),
                '1' => _t('保留1位小数'),
                '2' => _t('保留2位小数'),
                '3' => _t('保留3位小数')
            ),
            '2',
            _t('时间精确度'),
            _t('页面加载时间的小数点位数')
        );
        $form->addInput($precision);
        
        // 显示额外信息
        $showMemory = new Typecho_Widget_Helper_Form_Element_Radio(
            'showMemory',
            array(
                '0' => _t('不显示'),
                '1' => _t('显示')
            ),
            '0',
            _t('显示内存占用'),
            _t('是否显示PHP内存占用信息')
        );
        $form->addInput($showMemory);
        
        $showServer = new Typecho_Widget_Helper_Form_Element_Radio(
            'showServer',
            array(
                '0' => _t('不显示'),
                '1' => _t('显示')
            ),
            '0',
            _t('显示服务器信息'),
            _t('是否显示PHP版本和服务器信息')
        );
        $form->addInput($showServer);
        
        $showTypecho = new Typecho_Widget_Helper_Form_Element_Radio(
            'showTypecho',
            array(
                '0' => _t('不显示'),
                '1' => _t('显示')
            ),
            '0',
            _t('显示Typecho版本'),
            _t('是否显示Typecho版本信息')
        );
        $form->addInput($showTypecho);
        
        $showPluginVer = new Typecho_Widget_Helper_Form_Element_Radio(
            'showPluginVer',
            array(
                '0' => _t('不显示'),
                '1' => _t('显示')
            ),
            '0',
            _t('显示插件版本'),
            _t('是否显示LoadTime插件版本信息')
        );
        $form->addInput($showPluginVer);
        
        $showCopyright = new Typecho_Widget_Helper_Form_Element_Radio(
            'showCopyright',
            array(
                '0' => _t('不显示'),
                '1' => _t('显示')
            ),
            '1',
            _t('显示版权信息'),
            _t('是否显示插件版权信息')
        );
        $form->addInput($showCopyright);
        
        // 样式设置
        $fontColor = new Typecho_Widget_Helper_Form_Element_Text(
            'fontColor', null, '#888888',
            _t('字体颜色'), _t('字体颜色，使用十六进制颜色代码，默认为#888888（灰色）')
        );
        $form->addInput($fontColor);
        
        $fontSize = new Typecho_Widget_Helper_Form_Element_Text(
            'fontSize', null, '12',
            _t('字体大小'), _t('字体大小，单位为像素(px)，默认为12')
        );
        $form->addInput($fontSize);
        
        $fontFamily = new Typecho_Widget_Helper_Form_Element_Text(
            'fontFamily', null, 'Arial, Helvetica, sans-serif',
            _t('字体族'), _t('设置字体族，例如："Arial, Helvetica, sans-serif"')
        );
        $form->addInput($fontFamily);
        
        $useIcon = new Typecho_Widget_Helper_Form_Element_Radio(
            'useIcon',
            array(
                '0' => _t('不使用'),
                '1' => _t('使用')
            ),
            '0',
            _t('使用图标'),
            _t('是否使用图标美化显示（使用Font Awesome图标）')
        );
        $form->addInput($useIcon);
        
        $customCSS = new Typecho_Widget_Helper_Form_Element_Textarea(
            'customCSS', null, '',
            _t('自定义CSS'),
            _t('可以添加自定义CSS来美化显示效果，会自动添加到&lt;style&gt;标签中')
        );
        $form->addInput($customCSS);
        
        // 版本信息
        echo '<div class="loadtime-version">
            <p>LoadTime 插件版本: ' . self::VERSION . ' | 作者: <a href="https://github.com/Cyruss-X" target="_blank">Cyruss-X</a></p>
        </div>';
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 检查是否应该显示
     * 
     * @access private
     * @return boolean
     */
    private static function shouldDisplay()
    {
        $options = Helper::options()->plugin('LoadTime');
        
        // 防止空值错误
        if (empty($options)) {
            return true;
        }
        
        // 检查排除页面
        if (isset($options->excludePages) && !empty($options->excludePages)) {
            $request = Typecho_Request::getInstance();
            $pathinfo = $request->getPathinfo();
            $excludes = explode(',', $options->excludePages);
            
            foreach ($excludes as $exclude) {
                $exclude = trim($exclude);
                if (!empty($exclude) && strpos($pathinfo, $exclude) !== false) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * 在头部添加CSS和JavaScript
     * 
     * @access public
     * @return void
     */
    public static function renderHeader()
    {
        $options = Helper::options()->plugin('LoadTime');
        
        // 防止空值错误
        if (empty($options)) {
            return;
        }
        
        // 添加自定义CSS
        if (isset($options->customCSS) && !empty($options->customCSS)) {
            echo '<style type="text/css">' . $options->customCSS . '</style>';
        }
        
        // 添加Font Awesome
        if (isset($options->useIcon) && $options->useIcon == '1') {
            echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">';
        }
        
        // 添加显示方式相关的JavaScript
        $displayMode = isset($options->displayMode) ? $options->displayMode : 'always';
        if ($displayMode != 'always') {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    var loadTimeElements = document.querySelectorAll(".load-time-info");
                    ';
            
            if ($displayMode == 'hover') {
                echo 'loadTimeElements.forEach(function(element) {
                        element.style.opacity = "0";
                        element.addEventListener("mouseenter", function() {
                            this.style.opacity = "1";
                        });
                        element.addEventListener("mouseleave", function() {
                            this.style.opacity = "0";
                        });
                    });';
            } else if ($displayMode == 'click') {
                $fontColor = isset($options->fontColor) ? $options->fontColor : '#888888';
                $fontSize = isset($options->fontSize) ? $options->fontSize : '12';
                
                echo 'loadTimeElements.forEach(function(element) {
                        element.style.display = "none";
                        var toggle = document.createElement("div");
                        toggle.className = "load-time-toggle";
                        toggle.innerHTML = "显示页面信息";
                        toggle.style.cursor = "pointer";
                        toggle.style.textAlign = "center";
                        toggle.style.color = "' . $fontColor . '";
                        toggle.style.fontSize = "' . $fontSize . 'px";
                        toggle.style.margin = "10px 0";
                        element.parentNode.insertBefore(toggle, element);
                        
                        toggle.addEventListener("click", function() {
                            if (element.style.display === "none") {
                                element.style.display = "block";
                                this.innerHTML = "隐藏页面信息";
                            } else {
                                element.style.display = "none";
                                this.innerHTML = "显示页面信息";
                            }
                        });
                    });';
            }
            
            echo '});
            </script>';
        }
        
        // 仅在选择了页头显示或同时显示时才渲染
        $position = isset($options->position) ? $options->position : 'footer';
        if (($position == 'header' || $position == 'both') && self::shouldDisplay()) {
            self::render();
        }
    }
    
    /**
     * 在页脚显示加载时间
     * 
     * @access public
     * @return void
     */
    public static function renderFooter()
    {
        $options = Helper::options()->plugin('LoadTime');
        
        // 防止空值错误
        if (empty($options)) {
            return;
        }
        
        // 仅在选择了页脚显示或同时显示时才渲染
        $position = isset($options->position) ? $options->position : 'footer';
        if (($position == 'footer' || $position == 'both') && self::shouldDisplay()) {
            self::render();
        }
    }
    
    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render()
    {
        // 获取插件配置
        $options = Helper::options()->plugin('LoadTime');
        
        // 防止空值错误
        if (empty($options)) {
            echo '<div style="text-align:center;color:#888888;font-size:12px;margin:10px 0;">页面加载时间插件初始化失败</div>';
            return;
        }
        
        // 获取程序执行结束时间
        $endTime = microtime(true);
        // 获取程序开始执行的时间
        $startTime = Typecho_Request::getInstance()->getServer('REQUEST_TIME_FLOAT');
        // 计算运行时间
        $precision = isset($options->precision) ? intval($options->precision) : 2;
        
        // 根据选择的单位计算时间
        if (isset($options->timeUnit) && $options->timeUnit == 'second') {
            $loadTime = round(($endTime - $startTime), $precision);
            $timeSuffix = ' s';
        } else {
            $loadTime = round(($endTime - $startTime) * 1000, $precision);
            $timeSuffix = ' ms';
        }
        
        // 准备输出内容
        $sep = isset($options->separator) ? $options->separator : ' | ';
        
        // 添加图标
        $useIcon = isset($options->useIcon) ? $options->useIcon : '0';
        $timeIcon = $useIcon == '1' ? '<i class="fa fa-clock-o" aria-hidden="true"></i> ' : '';
        $memoryIcon = $useIcon == '1' ? '<i class="fa fa-server" aria-hidden="true"></i> ' : '';
        $serverIcon = $useIcon == '1' ? '<i class="fa fa-cogs" aria-hidden="true"></i> ' : '';
        $typechoIcon = $useIcon == '1' ? '<i class="fa fa-code" aria-hidden="true"></i> ' : '';
        $pluginIcon = $useIcon == '1' ? '<i class="fa fa-plug" aria-hidden="true"></i> ' : '';
        $copyrightIcon = $useIcon == '1' ? '<i class="fa fa-copyright" aria-hidden="true"></i> ' : '';
        
        // 使用配置中的后缀或根据时间单位选择的后缀
        $prefix = isset($options->prefix) ? $options->prefix : '页面加载时间: ';
        $configSuffix = isset($options->suffix) ? $options->suffix : '';
        $suffix = empty($configSuffix) ? $timeSuffix : $configSuffix;
        $output = $timeIcon . $prefix . $loadTime . $suffix;
        
        // 添加内存占用信息
        if (isset($options->showMemory) && $options->showMemory == '1') {
            $memory = round(memory_get_usage() / 1024 / 1024, 2);
            $output .= $sep . $memoryIcon . '内存占用: ' . $memory . ' MB';
        }
        
        // 添加服务器信息
        if (isset($options->showServer) && $options->showServer == '1') {
            $server = PHP_OS . ' / PHP ' . PHP_VERSION;
            $output .= $sep . $serverIcon . '服务器: ' . $server;
        }
        
        // 添加Typecho版本
        if (isset($options->showTypecho) && $options->showTypecho == '1' && defined('Typecho_Common::VERSION')) {
            $version = Typecho_Common::VERSION;
            $output .= $sep . $typechoIcon . 'Typecho: ' . $version;
        }
        
        // 添加插件版本
        if (isset($options->showPluginVer) && $options->showPluginVer == '1') {
            $output .= $sep . $pluginIcon . 'LoadTime: ' . self::VERSION;
        }
        
        // 添加版权信息
        if (isset($options->showCopyright) && $options->showCopyright == '1') {
            $output .= $sep . $copyrightIcon . '<a href="https://github.com/Cyruss-X" target="_blank" style="color:inherit;text-decoration:none;">Cyruss-X</a>';
        }
        
        $fontSize = isset($options->fontSize) ? $options->fontSize : '12';
        $fontColor = isset($options->fontColor) ? $options->fontColor : '#888888';
        $fontFamily = isset($options->fontFamily) ? $options->fontFamily : 'Arial, Helvetica, sans-serif';
        
        // 添加CSS样式，处理鼠标悬停效果
        $displayMode = isset($options->displayMode) ? $options->displayMode : 'always';
        $hoverStyle = $displayMode == 'hover' ? 'transition: opacity 0.3s ease;' : '';
        
        echo '<div class="load-time-info" style="text-align:center;color:' . $fontColor . ';font-size:' . $fontSize . 'px;font-family:' . $fontFamily . ';margin:10px 0;' . $hoverStyle . '">' . $output . '</div>';
    }
} 
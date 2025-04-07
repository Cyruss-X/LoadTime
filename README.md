# LoadTime - Typecho页面加载时间显示插件

一个强大且高度可定制的Typecho插件，用于在网站显示页面加载时间、内存占用、数据库查询次数、服务器信息等。

## 功能特性

- 可选择在页头、页脚或两者同时显示
- 可排除特定页面不显示
- 支持三种显示方式：始终显示、鼠标悬停显示、点击显示
- 内置调试模式开关，便于显示数据库查询次数等调试信息
- 可自定义显示文本、分隔符和精确度
- 支持秒(s)和毫秒(ms)两种时间单位
- 丰富的信息显示选项：
  - 页面加载时间
  - 内存占用情况
  - 数据库查询次数
  - 服务器信息和PHP版本
  - Typecho版本
  - 插件版本
  - 版权信息
- 支持Font Awesome图标美化
- 完全自定义CSS样式和字体

## 安装方法

1. 下载本插件，解压后将文件夹重命名为`LoadTime`
2. 上传至网站的`/usr/plugins/`目录
3. 登录网站后台，在"控制台">"插件"中找到"LoadTime"，点击"启用"

## 配置选项

插件提供了丰富的配置选项，可根据需要进行设置：

### 基础设置
- **显示位置**：选择在页脚、页头或两者同时显示
- **排除页面**：设置不显示加载时间的页面，多个页面用英文逗号分隔
- **显示方式**：选择始终显示、鼠标悬停显示或点击显示

### 调试设置
- **开启调试模式**：开启后可以显示数据库查询次数等调试信息

### 文本设置
- **前缀文本**：显示在加载时间前面的文字，默认为"页面加载时间: "
- **后缀文本**：显示在加载时间后面的文字，默认为自动根据时间单位选择
- **分隔符**：各项信息之间的分隔符，默认为" | "
- **时间单位**：选择使用秒(s)或毫秒(ms)作为时间单位
- **时间精确度**：控制页面加载时间的小数点位数（0-3）

### 信息显示
- **显示内存占用**：显示PHP内存占用
- **显示数据库查询次数**：显示数据库查询次数（需开启调试模式）
- **显示服务器信息**：显示服务器操作系统和PHP版本
- **显示Typecho版本**：显示Typecho的版本号
- **显示插件版本**：显示LoadTime插件的版本信息
- **显示版权信息**：显示插件作者版权信息

### 样式设置
- **字体颜色**：自定义文字颜色，使用十六进制颜色代码
- **字体大小**：自定义文字大小，单位为像素(px)
- **字体族**：设置字体族，例如："Arial, Helvetica, sans-serif"
- **使用图标**：启用Font Awesome图标美化显示
- **自定义CSS**：可添加自定义CSS进一步美化显示效果

## 效果展示

基础效果（秒为单位）：
```
页面加载时间: 0.123 s
```

基础效果（毫秒为单位）：
```
页面加载时间: 123.45 ms
```

开启全部选项后的效果：
```
📊 页面加载时间: 0.123 s | 💾 内存占用: 2.34 MB | 🔍 数据库查询: 15 次 | ⚙️ 服务器: Windows / PHP 7.4.30 | 📝 Typecho: 1.2.0 | 🔌 LoadTime: 1.0.0 | © Cyruss-X
```

鼠标悬停效果：将信息设置为鼠标悬停时才显示，平时隐藏，避免干扰
点击显示效果：只显示一个"显示页面信息"的按钮，点击后才显示详细信息

## 常见问题

**Q: 为什么看不到数据库查询次数？**  
A: 数据库查询次数只有在开启调试模式后才会显示，请在插件设置中开启调试模式。

**Q: 如何设置特定页面不显示加载时间？**  
A: 在"排除页面"选项中输入页面的URL路径部分，多个页面用英文逗号分隔。

**Q: 插件会影响网站加载速度吗？**  
A: 本插件非常轻量，对网站性能影响微乎其微。即便使用了全部功能，也不会明显增加加载时间。

**Q: 如何在移动设备上优化显示？**  
A: 可以使用自定义CSS针对移动设备进行样式调整，或使用"点击显示"模式减少对页面布局的影响。

**Q: 推荐使用哪种字体族设置？**  
A: 为了最佳兼容性，建议使用网页安全字体如："Arial, Helvetica, sans-serif"、"Georgia, serif"或"Courier New, monospace"。

## 作者

- 作者：Cyruss-X
- 版本：1.0.0
- GitHub：https://github.com/Cyruss-X 

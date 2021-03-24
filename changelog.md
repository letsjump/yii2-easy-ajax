### CHANGE LOG

**v1.0.6**
- BUG: actionModal() creates new records during validation if form parameter `enableAjaxValidation` is true. Added new method `EasyAjax::submit()` that checks if form submit button is clicked

**v1.0.5**
- Added reference to asset-packagist repo to composer.json
 
**v1.0.4**
- BUG: yea_pjax_reload won't reload more than one pjax container at once

**v1.0.3**
- BUG: The EasyAjax::modal() $footer parameter erroneously accepted a view path
- BUG: The JS extends method was erroneously named with the yea_ prefix

**v1.0.2**
- BUG: Removed unused `$registerAsset` from Gii generator

**v1.0.1**
- BUG: customConfiguration won't overwrite the configuration array (temporary workaround)
- EasyAjaxBase class: Removed useless variable $registerAsset

**v1.0.0**
- README update
- Replaced defaultOptions() with configuration() in Gii files

**v0.9.1**
- BREAKING CHANGE: Switched from **Widget** to **Component**
Breaking configuration change: please refer to the [documentation](README.md#configuration)
- Move source code to 'src' directory
- Documentation updated
- Moved custom parameters from Yii::app()->params to Component configuration array

**v0.9.0**
- first alpha release

**0.8.0**
- minor enhancements

**0.7.1**
- Added the possibility to extend methods (work in progress)

**v0.7.0**
- added Gii CRUD templates
- added modalClose function and removed success=true

**v0.6.0**
- Added Gii CRUD templates
- new ReloadPjax function
- major response enhancements. Every single response object is now an array with key=function name and value=passed data
- bug fixes

**v0.5.4**
- some JS improvements
- updated copyright

**v0.5.1**
- bugfix pub.resetModal

**v0.5.0**
- javascript refactoring
- function pub.resetModal 

**v0.4.0**
- javascript refactoring
- added GridView ActionColumn helper

**v0.3.0**
- added modal helper
- added modal template

**v0.2.0**
- created notify helper

- javascript object rewritten

**v0.1.5**
- created growl helper
- imported notify assets
- globally renamed growl to notify

**v0.1.0**
- initial release
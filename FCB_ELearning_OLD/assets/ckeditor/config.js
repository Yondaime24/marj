/**
 * @license Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
  // Define changes to default configuration here. For example:
  // config.language = 'fr';
  // config.uiColor = "#AADC6E";
  config.height = 465;
  // config.width = 1260;

config.toolbarGroups = [
    { name: 'clipboard', groups: [ 'undo', 'clipboard' ] },
    { name: 'tools', groups: [ 'tools' ] },
    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
    { name: 'forms', groups: [ 'forms' ] },
    { name: 'insert', groups: [ 'insert', 'Youtube', 'CodeSnippet' ] },
    { name: 'links', groups: [ 'links' ] },
    { name: 'styles', groups: [ 'styles' ] },
    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    { name: 'paragraph', groups: [ 'align', 'list', 'indent', 'blocks', 'bidi', 'paragraph' ] },
    { name: 'colors', groups: [ 'colors' ] },
    { name: 'others', groups: [ 'others' ] },
    { name: 'about', groups: [ 'about' ] }
  ];


  config.extraPlugins = "lineheight";
  config.removeButtons = 'About,Source,NewPage,Save,ExportPdf,Print,Scayt,Language';
  config.filebrowserImageBrowseUrl = "../assets/imageFolder/";
  config.filebrowserWindowWidth = "900";
  config.filebrowserWindowHeight = "500";
  // config.image_previewText = " ";
};

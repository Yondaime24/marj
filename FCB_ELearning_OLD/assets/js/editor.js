  CKEDITOR.replace( 'editor1' );

    //FOR INLINE EDITOR
        //    CKEDITOR.disableAutoInline = true;
        //    CKEDITOR.inline( 'editor1' );

    CKEDITOR.on('dialogDefinition', function(e) {
            dialogName = e.data.name;
            dialogDefinition = e.data.definition;
            console.log(dialogName);
            if (dialogName == 'image') {
                dialogDefinition.removeContents('Link')
                dialogDefinition.removeContents('advanced')
                var tabContent = tabContent = dialogDefinition.getContents('info');
            }
        })

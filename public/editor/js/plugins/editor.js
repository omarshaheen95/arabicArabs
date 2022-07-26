const editorInstance = new FroalaEditor('.edit', {
    key: "uXD2lA5D6C4F4G3A3konmA2A-9oC-7H-7ibC4bvddtD3jefpF1F1E1G4F1C11B8C2E5D3==",
    fontFamilySelection: true,
    heightMin: 200,
    paragraphStyles: {
        leftDirection: 'Left Direction',
        rightDirection: 'Right Direction'
    },
    toolbarButtons: {
        'moreText': {
            'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'inlineClass', 'inlineStyle', 'clearFormatting']
        },
        'moreParagraph': {
            'buttons': ['alignLeft', 'alignCenter', 'paragraphStyle', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'formatOLSimple', 'lineHeight', 'outdent', 'indent', 'quote']
        },
        'moreRich': {
            'buttons': ['insertLink', 'insertImage', 'insertVideo', 'insertTable', 'emoticons', 'fontAwesome', 'specialCharacters', 'embedly', 'insertFile', 'insertHR']
        },
        'moreMisc': {
            'buttons': ['undo', 'redo', 'fullscreen', 'print', 'getPDF', 'spellChecker', 'selectAll', 'html', 'help']
        }
    },
    fontFamily: {
        'Arial,Helvetica,sans-serif': 'Arial',
        'Georgia,serif': 'Georgia',
        'Impact,Charcoal,sans-serif': 'Impact',
        'Tahoma,Geneva,sans-serif': 'Tahoma',
        "'Times New Roman',Times,serif": 'Times New Roman',
        'Verdana,Geneva,sans-serif': 'Verdana'
    },
    enter: FroalaEditor.ENTER_P,
    placeholderText: null,

    colorsStep: 6,
    colorsText: [
        '#15E67F', '#E3DE8C', '#D8A076', '#D83762', '#76B6D8', 'REMOVE',
        '#1C7A90', '#249CB8', '#4ABED9', '#FBD75B', '#FBE571', '#FFFFFF', '#000000'
    ],
    toolbarButtonsXS: [['undo', 'redo'], ['bold', 'italic', 'underline']],
    fontSize: ['8', '10', '12', '14', '18', '22', '24', '28', '32', '36', '40', '44', '48', '52', '56', '60', '96'],
    // Set the image upload parameter.
    imageUploadParam: 'imageFile',

    // Set the image upload URL.
    imageUploadURL: UPLOAD_ROUTE,

    // Additional upload params.
    imageUploadParams: {
        "_token": CSRF,
    },

    // Set request type.
    imageUploadMethod: 'POST',

    // Set max image size to 5MB.
    imageMaxSize: 5 * 1024 * 1024,

    // Allow to upload PNG and JPG.
    imageAllowedTypes: ['jpeg', 'jpg', 'png'],

    events: {
        initialized: function () {
            const editor = this
            this.el.closest('form').addEventListener('submit', function (e) {
                console.log(editor.$oel.val());
            })
        },
        'image.beforeUpload': function (images) {

            console.log("// Return false if you want to stop the image upload.");
        },
        'image.uploaded': function (response) {

            console.log("// Image was uploaded to the server.");
        },
        'image.inserted': function ($img, response) {

            console.log("// Image was inserted in the editor.");
        },
        'image.replaced': function ($img, response) {

            console.log("// Image was replaced in the editor.");
        },
        'image.error': function (error, response) {
            // Bad link.
            if (error.code == 1) {
                console.log(error + " - " + error.code);
            }

            // No link in upload response.
            else if (error.code == 2) {
                console.log(error + " - " + error.code);
            }

            // Error during image upload.
            else if (error.code == 3) {
                console.log(error + " - " + error.code);
            }

            // Parsing response failed.
            else if (error.code == 4) {
                console.log(error + " - " + error.code);
            }

            // Image too text-large.
            else if (error.code == 5) {
                console.log(error + " - " + error.code);
            }

            // Invalid image type.
            else if (error.code == 6) {
                console.log(error + " - " + error.code);
            }

            // Image can be uploaded only to same domain in IE 8 and IE 9.
            else if (error.code == 7) {
                console.log(error + " - " + error.code);
            }

            // Response contains the original server response to the request if available.
        }
    }
});


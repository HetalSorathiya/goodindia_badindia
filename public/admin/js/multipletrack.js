ProjectName.modules.MultipletrackListing = {
    init: function() {
        this.Dropzone();
        this.historyback();
       
    },
    historyback: function() {
        jQuery('.cancelbutton').click(function() {
            window.history.back();
        });
   },
    Dropzone: function() {

        //var Dropzone = require("dropzone");
        //Drag n Drop up-loader for DOCUMENT
        //Dropzone.options.myAwesomeDropzone = false;
        Dropzone.autoDiscover = false;
        //alert("here");
        //Drag n Drop up-loader for EXTRA_IMAGE
        var paramsdata = {};
        paramsdata['UNIQUEIDENTIFIER'] = UNIQUEIDENTIFIER;
        //alert(UNIQUEIDENTIFIER);
        //alert( BASE_PATH + '/admin/product/upload');

        $(".audio_dropzone").dropzone(
                {
                    url: BASE_PATH + '/admin/track/upload',
                    paramName: 'AUDIO',
                    params: paramsdata,
                    //  maxFilesize: 500,
                    // acceptedFiles: ".mp3",
                }
        );
    },
};

      // -----
      // Configuration:
      // https://www.bytescale.com/docs/upload-widget#configuration
      // -----
      const options = {
        apiKey: "public_kW15cKh5tYscAjaDNRfdgprfiCLP", // This is your API key.

        maxFileCount: 10,

        // Dropzone configuration:
        layout: "inline",
        container: "#upload-widget-container",

        showFinishButton: true,

        // To remove the 'finish' button:
        // showFinishButton: false,
        // onUpdate: ({ uploadedFiles, pendingFiles, failedFiles }) => {
        //   const fileUrls = uploadedFiles.map(x => x.fileUrl).join("\n");
        //   if (fileUrls.length > 0) {
        //     alert(`File(s) uploaded:\n\n${fileUrls}`);
        //   }
        // }
      };

      // import * as Bytescale from "@bytescale/upload-widget";
      Bytescale.UploadWidget.open(options).then(
        files => {
          const fileUrls = files.map(x => x.fileUrl).join("\n");
          const success = fileUrls.length === 0
            ? "No file selected."
            : `File uploaded:\n\n${fileUrls}`;
          alert(success);
        },
        error => {
          alert(error);
        }
      );
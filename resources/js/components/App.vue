<template>
    <div>
        <div class="mt-4">
            <file-pond
                name="image"
                ref="pond"
                label-idle="Click to choose image, or drag here..."
                @init="filepondInitialized"
                accepted-file-types="image/jpg, image/jpeg, image/png"
                @processfile="handleProcessedFile"
                max-file-size="1MB"
            />
        </div>
        <div class="mt-8 mb-24">
            <h3 class="text-2xl font-medium text-center">Image Gallery</h3>
            <div class="grid grid-cols-3 gap-2 justify-evenly mt-4">
                <div v-for="(image, index) in images" :key="index">
                    <img :src="'/storage/images/' + image">
                </div>
            </div>
        </div>
</template>
<script>
import vueFilePond, { setOptions } from 'vue-filepond';
import "filepond/dist/filepond.min.css";
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';

let serverMessage = {};
setOptions({
    server: {
        process: {
            url: './upload',
            onerror: (response) => {
                serverMessage = JSON.parse(response);
            },
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf_token"]').content
            }
        }
    },
    labelFileProcessingError: () => {
        return serverMessage.error;
    }
});

const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginFileValidateSize);

export default {
    components: {
        FilePond
    },
    data() {
        return {
            images: []
        }
    },
    mounted() {
        axios.get('/images')
            .then((response) => {
                this.images = response.data;
            })
            .catch((error) => {
                console.error(error);
            });
    },
    methods: {
        filepondInitialized() {
            console.log('Filepond is ready!');
            console.log('Filepond object:', this.$refs.pond);
        },
        handleProcessedFile(error, file) {
            if (error) {
                console.error(error);
                return;
            }

            // add the file to our images array
            this.images.unshift(file.serverId);
        }
    }
}
</script>
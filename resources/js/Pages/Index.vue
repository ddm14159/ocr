<script>
import { useForm } from "@inertiajs/vue3";
import 'vue-advanced-cropper/dist/style.css'

export default {
    props: {
        text: Array,
    },
    data() {
        return {
            file: '',
            url: '',
            preview: '',
            coordinates: [],
            form: useForm({
                image: null,
                coordinates: [],
            }),
            line: '',
            copyLine: '',
        };
    },
    methods: {
        getListAreas(values) {
            this.coordinates = values.map((i) => [i.x, i.y, i.width, i.height]);

        },
        handleFileUpload() {
            this.preview = document.getElementById('c-crop--hide_img');
            this.file = this.$refs.file.files[0]
            if (this.file) {
                this.preview.src = URL.createObjectURL(this.file);
            }
            this.url = this.preview.src;
        },
        submit() {
            this.form.image = this.$refs.file.files[0];
            this.form.coordinates = this.coordinates;
            this.form.post('/');
        },
        copyTextToClipboard(line) {
            navigator.clipboard.writeText(line);
        },
    },

};
</script>

<template>
    <form @submit.prevent="submit" enctype="multipart/form-data" class="d-flex justify-content-center align-items-center">
        <div class="row">
            <div class="col-auto ">
                <div class="form-group mr-2 ">
                    <input
                        ref="file"
                        type="file"
                        id="selectImage"
                        @input="handleFileUpload"
                        class="form-control-file"
                    />
                </div>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary">Распознать</button>
            </div>
        </div>
        <input type="hidden" v-model="coordinates">
    </form>
    <div class="d-flex justify-content-center align-items-center mt-5">
        <multi-select-areas-image :cropUrl="url" v-on:getListAreas="getListAreas"/>
    </div>

    <div v-for="line in text" class="row d-flex justify-content-center align-items-center mt-5">
        <div class="col-auto d-flex align-items-center justify-content-center">
            {{ line.toString() }}
        </div>
        <div class="">
            <button class="btn btn-primary" type="button" @click="copyTextToClipboard(line)">Копировать</button>
        </div>
    </div>

</template>

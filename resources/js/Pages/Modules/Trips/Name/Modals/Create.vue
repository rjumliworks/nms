<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(!editable) ? 'New Name' : 'Edit Name'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12">
                    <InputLabel for="type" value="Type" :message="form.errors.type"/>
                    <select id="type" v-model="form.type" class="form-select" @change="handleInput('type')">
                        <option v-for="t in types" :key="t" :value="t">{{ t }}</option>
                    </select>
                </BCol>
                <BCol lg="12">
                    <InputLabel for="name" value="Name" :message="form.errors.name"/>
                    <TextInput id="name" v-model="form.name" type="text" class="form-control" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="12" v-if="editable">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" v-model="form.is_active">
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit()" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput },
    props: {
        types: { type: Array, default: () => [] },
        defaultType: { type: String, default: null }
    },
    data(){
        return {
            form: useForm({
                id: null,
                name: null,
                type: null,
                is_active: true,
                editable: false
            }),
            showModal: false,
            editable: false
        }
    },
    methods: {
        show(){
            this.editable = false;
            this.form.reset();
            this.form.editable = false;
            this.form.type = this.defaultType || (this.types[0] ?? null);
            this.form.is_active = true;
            this.showModal = true;
        },
        edit(data){
            this.form.id = data.id;
            this.form.name = data.name;
            this.form.type = data.type;
            this.form.is_active = !!data.is_active;
            this.editable = true;
            this.form.editable = true;
            this.showModal = true;
        },
        submit(){
            this.form.post('/names', {
                preserveScroll: true,
                onSuccess: () => {
                    this.$emit('update', true);
                    this.hide();
                }
            });
        },
        handleInput(field){
            this.form.errors[field] = false;
        },
        hide(){
            this.form.reset();
            this.form.clearErrors();
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>

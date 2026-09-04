<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(!editable) ? 'New Buyer' : 'Edit Buyer'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12">
                    <InputLabel for="firstname" value="First Name" :message="form.errors.firstname"/>
                    <TextInput id="firstname" v-model="form.firstname" type="text" class="form-control" @input="handleInput('firstname')" :light="true"/>
                </BCol>
                <BCol lg="12">
                    <InputLabel for="middlename" value="Middle Name (optional)" :message="form.errors.middlename"/>
                    <TextInput id="middlename" v-model="form.middlename" type="text" class="form-control" @input="handleInput('middlename')" :light="true"/>
                </BCol>
                <BCol lg="12">
                    <InputLabel for="lastname" value="Last Name" :message="form.errors.lastname"/>
                    <TextInput id="lastname" v-model="form.lastname" type="text" class="form-control" @input="handleInput('lastname')" :light="true"/>
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
    data(){
        return {
            form: useForm({
                id: null,
                firstname: null,
                middlename: null,
                lastname: null,
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
            this.form.is_active = true;
            this.showModal = true;
        },
        edit(data){
            this.form.id = data.id;
            this.form.firstname = data.firstname;
            this.form.middlename = data.middlename;
            this.form.lastname = data.lastname;
            this.form.is_active = !!data.is_active;
            this.editable = true;
            this.form.editable = true;
            this.showModal = true;
        },
        submit(){
            this.form.post('/buyers', {
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

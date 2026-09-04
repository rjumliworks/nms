<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(!editable) ? 'New Trip' : 'Edit Trip'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12">
                    <InputLabel for="boat" value="Boat" :message="form.errors.boat_id"/>
                    <Multiselect :options="boatOptions" label="name" v-model="boat" object :searchable="true" placeholder="Select Boat" ref="boatSelect"/>
                </BCol>
                <BCol lg="12">
                    <InputLabel for="date" value="Date" :message="form.errors.date"/>
                    <TextInput id="date" v-model="form.date" type="date" class="form-control" @input="handleInput('date')" :light="true"/>
                </BCol>
                <BCol lg="12">
                    <InputLabel for="note" value="Note"/>
                    <Textarea id="note" v-model="form.note" @input="handleInput('note')" :light="true"/>
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
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import Textarea from '@/Shared/Components/Forms/Textarea.vue';
export default {
    components: { InputLabel, TextInput, Textarea, Multiselect },
    props: {
        boats: { type: Array, default: () => [] }
    },
    data(){
        return {
            form: useForm({
                id: null,
                boat_id: null,
                date: null,
                note: null,
                editable: false
            }),
            boat: null,
            showModal: false,
            editable: false
        }
    },
    computed: {
        boatOptions(){
            return (this.boats || []).map(item => ({ value: item.id, name: item.name }));
        }
    },
    watch: {
        boat(newVal){
            this.form.boat_id = newVal ? newVal.value : null;
        }
    },
    methods: {
        show(){
            this.editable = false;
            this.form.editable = false;
            this.showModal = true;
        },
        edit(data){
            this.form.id = data.id;
            this.form.date = data.date;
            this.form.note = data.note;
            if(data.boat){
                this.boat = { value: data.boat.id, name: data.boat.name };
                this.form.boat_id = data.boat.id;
            }
            this.editable = true;
            this.form.editable = true;
            this.showModal = true;
        },
        submit(){
            this.form.post('/trips', {
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
            this.boat = null;
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>

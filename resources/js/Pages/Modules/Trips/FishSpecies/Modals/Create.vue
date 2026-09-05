<template>
    <b-modal v-model="showModal" size="lg" header-class="p-3 bg-light" :title="(!editable) ? 'New Fish Species' : 'Edit Fish Species'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="6">
                    <InputLabel for="name" value="Market / Filipino Name" :message="form.errors.name"/>
                    <TextInput id="name" v-model="form.name" type="text" class="form-control" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="6">
                    <InputLabel for="local_name" value="Local Name (optional)" :message="form.errors.local_name"/>
                    <TextInput id="local_name" v-model="form.local_name" type="text" class="form-control" @input="handleInput('local_name')" :light="true"/>
                </BCol>
                <BCol lg="6">
                    <InputLabel for="english_name" value="English Name (optional)" :message="form.errors.english_name"/>
                    <TextInput id="english_name" v-model="form.english_name" type="text" class="form-control" @input="handleInput('english_name')" :light="true"/>
                </BCol>
                <BCol lg="6">
                    <InputLabel for="tausug_name" value="Tausug Name (optional)" :message="form.errors.tausug_name"/>
                    <TextInput id="tausug_name" v-model="form.tausug_name" type="text" class="form-control" placeholder="Leave blank if not documented" @input="handleInput('tausug_name')" :light="true"/>
                </BCol>
                <BCol lg="8">
                    <InputLabel for="scientific_name" value="Scientific Name (optional)" :message="form.errors.scientific_name"/>
                    <TextInput id="scientific_name" v-model="form.scientific_name" type="text" class="form-control" @input="handleInput('scientific_name')" :light="true"/>
                </BCol>
                <BCol lg="4">
                    <InputLabel for="unit" value="Unit" :message="form.errors.unit"/>
                    <TextInput id="unit" v-model="form.unit" type="text" class="form-control" placeholder="kg" @input="handleInput('unit')" :light="true"/>
                </BCol>
                <BCol lg="12">
                    <InputLabel for="category" value="Category (optional)" :message="form.errors.category_id"/>
                    <Multiselect :options="categories" label="name" v-model="category" object :searchable="true" :can-clear="true" placeholder="Select Category"/>
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
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput, Multiselect },
    props: {
        categories: { type: Array, default: () => [] }
    },
    data(){
        return {
            form: useForm({
                id: null,
                name: null,
                local_name: null,
                english_name: null,
                tausug_name: null,
                scientific_name: null,
                category_id: null,
                unit: 'kg',
                is_active: true,
                editable: false
            }),
            category: null,
            showModal: false,
            editable: false
        }
    },
    watch: {
        category(newVal){
            this.form.category_id = newVal ? newVal.value : null;
        }
    },
    methods: {
        show(){
            this.editable = false;
            this.form.reset();
            this.form.editable = false;
            this.form.unit = 'kg';
            this.form.is_active = true;
            this.category = null;
            this.showModal = true;
        },
        edit(data){
            this.form.id = data.id;
            this.form.name = data.name;
            this.form.local_name = data.local_name;
            this.form.english_name = data.english_name;
            this.form.tausug_name = data.tausug_name;
            this.form.scientific_name = data.scientific_name;
            this.form.unit = data.unit;
            this.form.is_active = !!data.is_active;
            if(data.category){
                this.category = { value: data.category.id, name: data.category.name };
                this.form.category_id = data.category.id;
            }
            this.editable = true;
            this.form.editable = true;
            this.showModal = true;
        },
        submit(){
            this.form.post('/fish-species', {
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
            this.category = null;
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>

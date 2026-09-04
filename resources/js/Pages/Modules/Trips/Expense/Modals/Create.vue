<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(!editable) ? 'New Expense' : 'Edit Expense'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12">
                    <InputLabel for="category" value="Category" :message="form.errors.category_id"/>
                    <Multiselect :options="categoryOptions" label="name" v-model="category" object :searchable="true" placeholder="Select Category"/>
                </BCol>
                <BCol lg="12">
                    <InputLabel for="trip" value="Trip"/>
                    <select id="trip" v-model="form.trip_id" class="form-select" @change="handleInput('trip_id')">
                        <option :value="null">Select Trip</option>
                        <option v-for="trip in trips" v-bind:key="trip.id" :value="trip.id">{{ trip.code }} - {{ trip.date }}</option>
                    </select>
                </BCol>
                <BCol lg="12">
                    <InputLabel for="payee" value="Payee" :message="form.errors.name_id"/>
                    <NameSearch v-model="payee" type="Person" :options="names.Person || []" placeholder="Search payee"/>
                </BCol>
                <BCol lg="12">
                    <InputLabel for="amount" value="Amount" :message="form.errors.amount"/>
                    <TextInput id="amount" v-model="form.amount" type="number" class="form-control" placeholder="Please enter amount" @input="handleInput('amount')" :light="true"/>
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
import NameSearch from '../../Shared/NameSearch.vue';
export default {
    components: { InputLabel, TextInput, Textarea, Multiselect, NameSearch },
    props: {
        categories: { type: Array, default: () => [] },
        names: { type: Object, default: () => ({}) }
    },
    data(){
        return {
            form: useForm({
                id: null,
                amount: null,
                note: null,
                category_id: null,
                name_id: null,
                trip_id: null,
                editable: false
            }),
            category: null,
            payee: null,
            trips: [],
            showModal: false,
            editable: false
        }
    },
    computed: {
        categoryOptions(){
            if(this.categories && this.categories.length){
                return this.categories;
            }
            // Fallback when no `categories` prop is supplied by the backend.
            return [ { value: null, name: 'Hull Boat' }, { value: null, name: 'Carrier' } ];
        }
    },
    watch: {
        category(newVal){
            this.form.category_id = newVal ? newVal.value : null;
        },
        payee(newVal){
            this.form.name_id = newVal ? newVal.value : null;
        }
    },
    mounted(){
        this.fetchTrips();
    },
    methods: {
        fetchTrips(){
            axios.get('/trips', { params: { options: 'lists', counts: 50 } })
            .then(response => {
                this.trips = response.data.data;
            })
            .catch(err => console.log(err));
        },
        show(){
            this.editable = false;
            this.form.editable = false;
            this.showModal = true;
        },
        edit(data){
            this.form.id = data.id;
            this.form.amount = data.amount;
            this.form.note = data.note;
            if(data.category){
                this.category = { value: data.category.id, name: data.category.name };
                this.form.category_id = data.category.id;
            }
            if(data.name){
                this.payee = { value: data.name.id, name: data.name.name };
                this.form.name_id = data.name.id;
            }
            if(data.trip){
                this.form.trip_id = data.trip.id;
            }
            this.editable = true;
            this.form.editable = true;
            this.showModal = true;
        },
        submit(){
            this.form.post('/expenses', {
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
            this.payee = null;
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>

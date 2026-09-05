<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Record Payment" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div v-if="loan">
            <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light-subtle rounded border">
                <div>
                    <p class="text-uppercase fw-semibold fs-11 text-muted mb-0">Recipient</p>
                    <h6 class="mb-0">{{ loan.employee ? loan.employee.name : '-' }}</h6>
                </div>
                <div class="text-end">
                    <p class="text-uppercase fw-semibold fs-11 text-muted mb-0">Remaining Balance</p>
                    <h6 class="mb-0 text-danger">{{ formatCurrency(loan.balance) }}</h6>
                </div>
            </div>
            <form class="customform">
                <BRow class="g-3">
                    <BCol lg="12">
                        <InputLabel for="amount" value="Payment Amount" :message="form.errors.amount"/>
                        <Amount @amount="setAmount" ref="amountInput" :readonly="false" @input="handleInput('amount')"/>
                    </BCol>
                    <BCol lg="12">
                        <InputLabel for="note" value="Note (optional)"/>
                        <Textarea v-model="form.note" :modelSize="2" placeholder="e.g. partial payment, deducted from salary" @input="handleInput('note')" :light="true"/>
                    </BCol>
                </BRow>
            </form>
            <div class="mt-4" v-if="loan.payments && loan.payments.length">
                <p class="text-uppercase fw-semibold fs-11 text-muted mb-2">Payment History</p>
                <div class="table-responsive" style="max-height: 180px; overflow-y: auto;">
                    <table class="table table-sm align-middle table-striped mb-0 fs-12">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th class="text-end">Amount</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="payment in loan.payments" v-bind:key="payment.id">
                                <td>{{ payment.created_at }}</td>
                                <td class="text-end">{{ formatCurrency(payment.amount) }}</td>
                                <td>{{ payment.note || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit()" variant="primary" :disabled="form.processing || !loan || loan.balance <= 0" block>Record Payment</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import Textarea from '@/Shared/Components/Forms/Textarea.vue';
import Amount from '@/Shared/Components/Forms/Amount.vue';
export default {
    components: { InputLabel, Textarea, Amount },
    data(){
        return {
            form: useForm({
                id: null,
                amount: null,
                note: null,
                option: 'payment'
            }),
            loan: null,
            showModal: false
        }
    },
    methods: {
        show(loan){
            this.loan = loan;
            this.form.id = loan.id;
            this.showModal = true;
            this.$nextTick(() => {
                this.$refs.amountInput.emitValue(loan.balance);
            });
        },
        setAmount(val){
            this.form.amount = val;
        },
        submit(){
            this.form.post('/cash-advances', {
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
            this.loan = null;
            this.showModal = false;
        },
        formatCurrency(value){
            return '₱' + Number(value ?? 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }
}
</script>

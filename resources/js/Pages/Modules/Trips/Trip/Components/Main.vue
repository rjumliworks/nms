<template>
    <div class="card bg-light-subtle shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-file-list-3-fill text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">Trip Transactions</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">Expenses, cash advances, sales, and carriers recorded for this trip</p>
                </div>
                <template v-if="type == 'Carriers'">
                    <div class="flex-shrink-0">
                        <BButton @click="addCarrier()" variant="danger" class="btn-sm waves-effect waves-light mt-1">
                            Add Carrier
                        </BButton>
                    </div>
                </template>
                <template v-if="type == 'Sales'">
                    <div class="flex-shrink-0">
                        <BButton @click="addSale()" variant="danger" class="btn-sm waves-effect waves-light mt-1">
                            Add Sale
                        </BButton>
                    </div>
                </template>
                <template v-if="type == 'Expenses'">
                    <div class="flex-shrink-0">
                        <BButton @click="addExpense()" variant="danger" class="btn-sm waves-effect waves-light mt-1">
                            Add Expense
                        </BButton>
                    </div>
                </template>
                <template v-if="type == 'Cash-Advance'">
                    <div class="flex-shrink-0">
                        <BButton @click="addCashAdvance()" variant="danger" class="btn-sm waves-effect waves-light mt-1">
                            Add Cash Advance
                        </BButton>
                    </div>
                </template>
            </div>
        </div>

        <div class="card bg-white rounded-bottom shadow-none mb-0">
            <div class="step-arrow-nav mt-0">
                <ul class="nav nav-pills nav-justified custom-nav" role="tablist">
                    <li class="nav-item" role="presentation" v-for="(menu, index) in menus" v-bind:key="index">
                        <button class="nav-link fs-12 p-3" :class="(index == 0) ? 'active' : ''"
                            :id="menu+'-tab'" data-bs-toggle="pill" :data-bs-target="'#'+menu"
                            type="button" role="tab" :aria-controls="menu" aria-selected="true" @click="type = menu">
                            {{ menu.replace('-',' ') }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body bg-white rounded-bottom">
            <div class="tab-content">
                <div class="tab-pane" :class="(index == 0) ? 'show active' : ''" :id="menu" role="tabpanel" :aria-labelledby="menu+'-tab'" v-for="(menu, index) in menus" v-bind:key="index">
                    <div class="carousel-container">
                        <div class="carousel-content">
                            <transition mode="out-in">
                                <div :key="index" class="tab-content">
                                    <Expenses ref="expensesTab" :trip="trip" :categories="categories.Expense" :names="names" v-if="menu == 'Expenses'" @update="$emit('refresh')"/>
                                    <CashAdvance ref="cashAdvanceTab" :trip="trip" :categories="categories.Loan" :names="names" v-if="menu == 'Cash-Advance'" @update="$emit('refresh')"/>
                                    <Sales ref="salesTab" :trip="trip" :names="names" v-if="menu == 'Sales'" @update="$emit('refresh')"/>
                                    <Carriers :trip="trip" v-if="menu == 'Carriers'"/>
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Expenses from './Pages/Expenses.vue';
import CashAdvance from './Pages/CashAdvance.vue';
import Sales from './Pages/Sales.vue';
import Carriers from './Pages/Carriers.vue';
export default {
    components: { Expenses, CashAdvance, Sales, Carriers },
    props: ['trip','names','categories'],
    emits: ['refresh','add-carrier'],
    data(){
        return {
            menus: ['Carriers','Sales','Expenses','Cash-Advance'],
            type: 'Carriers'
        }
    },
    methods: {
        addCarrier(){
            this.$emit('add-carrier');
        },
        addSale(){
            this.$refs.salesTab[0].openCreate();
        },
        addExpense(){
            this.$refs.expensesTab[0].openCreate();
        },
        addCashAdvance(){
            this.$refs.cashAdvanceTab[0].openCreate();
        }
    }
}
</script>

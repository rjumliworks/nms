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
                    <p class="text-muted text-truncate-two-lines fs-12">Expenses, cash advances, and sales recorded for this trip</p>
                </div>
            </div>
        </div>

        <div class="card bg-white rounded-bottom shadow-none mb-0">
            <div class="step-arrow-nav mt-0">
                <ul class="nav nav-pills nav-justified custom-nav" role="tablist">
                    <li class="nav-item" role="presentation" v-for="(menu, index) in menus" v-bind:key="index">
                        <button class="nav-link fs-12 p-3" :class="(index == 0) ? 'active' : ''"
                            :id="menu+'-tab'" data-bs-toggle="pill" :data-bs-target="'#'+menu"
                            type="button" role="tab" :aria-controls="menu" aria-selected="true">
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
                                    <Expenses :trip="trip" :categories="categories.Expense" :names="names" v-if="menu == 'Expenses'" />
                                    <CashAdvance :trip="trip" :categories="categories.Loan" :names="names" v-if="menu == 'Cash-Advance'" />
                                    <Sales :trip="trip" :names="names" v-if="menu == 'Sales'" />
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
export default {
    components: { Expenses, CashAdvance, Sales },
    props: ['trip','names','categories'],
    data(){
        return {
            menus: ['Expenses','Cash-Advance','Sales']
        }
    }
}
</script>

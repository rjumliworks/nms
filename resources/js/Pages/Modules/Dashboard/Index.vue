<template>
    <PageHeader title="Dashboard" pageTitle="NMS" />

    <BRow class="g-3">
       
        <!-- Active trip -->
        <div class="col-md-12">
            <div class="card border shadow-none">
                <div class="card-body" v-if="activeTrip">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle fs-20">
                                        <i class="ri-sailboat-fill"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-0 fs-14">
                                    Active Trip &mdash; {{ activeTrip.code }}
                                    <span class="badge fs-10 ms-1" :class="activeTrip.has_departed ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary'">
                                        {{ activeTrip.has_departed ? 'Departed' : 'Preparing' }}
                                    </span>
                                </h5>
                                <p class="text-muted fs-12 mb-0">
                                    {{ activeTrip.boat?.name ?? '-' }} &middot; {{ activeTrip.date }}
                                    &middot; {{ carrierCount }} carrier{{ carrierCount === 1 ? '' : 's' }}
                                    &middot; {{ tubCount }} tub{{ tubCount === 1 ? '' : 's' }}
                                </p>
                            </div>
                        </div>
                        <form action="javascript:void(0);">
                                <div class="row g-3 mb-0 align-items-center">
                                    <div class="col-sm-auto">
                                        <div class="input-group">
                                            <select v-model="month" style="width: 170px;"  class="form-select" aria-label="Default select example">
                                                <option :value="null">All Months</option>
                                                <option :value="list" v-for="list in months" v-bind:key="list">{{list}}</option>
                                            </select>
                                            <select v-model="year" style="width: 150px;"  class="form-select" aria-label="Default select example">
                                                <option :value="null">All Years</option>
                                                <option :value="list" v-for="list in years" v-bind:key="list">{{list}}</option>
                                            </select>
                                            <div class="input-group-text bg-primary border-primary text-white">
                                                <i class="ri-calendar-2-line"></i> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
                <div class="card-body text-center py-4" v-else>
                    <p class="text-muted fs-13 mb-2">No active trip right now.</p>
                    <Link href="/trips" class="btn btn-sm btn-primary">Start a New Trip</Link>
                </div>
            </div>
        </div>

        <b-col lg="9" class="mt-n2">
            <BRow class="g-3">
                <!-- Category summary cards -->
                <div class="col-md-4">
                    <div class="card shadow-none border">
                        <div class="card-header bg-light-subtle">
                            <div class="d-flex mb-n3">
                                <div class="flex-shrink-0 me-3">
                                    <div style="height:2rem;width:2rem;">
                                        <span class="avatar-title bg-primary text-primary rounded-circle fs-4">
                                            <i class="ri-sailboat-fill text-light align-middle"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 fs-14"><span class="text-body">Trips</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-12">Total trips recorded</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <p class="mb-0 mt-2 text-primary text-center fw-semibold fs-16">{{ stats.trips }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-white shadow-none mb-0" style="height: 190px; overflow: auto;">
                            <ul class="list-group list-group-flush border-dashed mb-0 mt-0 p-2">
                                <li class="list-group-item" v-for="(item,index) in tripStatusList" v-bind:key="index">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 avatar-xs">
                                            <span class="avatar-title rounded-circle" :class="'bg-'+item.color+'-subtle text-'+item.color">
                                                <i class="ri-checkbox-circle-fill"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0 fs-13">{{ item.name }}</h6>
                                            <p class="text-muted mb-0 fs-11">{{ item.description }}</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <h5 class="mb-0 fs-14">{{ item.count }}</h5>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-none border">
                        <div class="card-header bg-light-subtle">
                            <div class="d-flex mb-n3">
                                <div class="flex-shrink-0 me-3">
                                    <div style="height:2rem;width:2rem;">
                                        <span class="avatar-title bg-primary text-primary rounded-circle fs-4">
                                            <i class="ri-shopping-bag-fill text-light align-middle"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 fs-14"><span class="text-body">Sales</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-12">Total sale transactions</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <p class="mb-0 mt-2 text-primary text-center fw-semibold fs-16">{{ salesBreakdown.total }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-white shadow-none mb-0" style="height: 190px; overflow: auto;">
                            <ul class="list-group list-group-flush border-dashed mb-0 mt-0 p-2">
                                <li class="list-group-item" v-for="(item,index) in salesStatusList" v-bind:key="index">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 avatar-xs">
                                            <span class="avatar-title rounded-circle" :class="'bg-'+item.color+'-subtle text-'+item.color">
                                                <i class="ri-checkbox-circle-fill"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0 fs-13">{{ item.name }}</h6>
                                            <p class="text-muted mb-0 fs-11">{{ item.description }}</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <h5 class="mb-0 fs-14">{{ item.count }}</h5>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-none border">
                        <div class="card-header bg-light-subtle">
                            <div class="d-flex mb-n3">
                                <div class="flex-shrink-0 me-3">
                                    <div style="height:2rem;width:2rem;">
                                        <span class="avatar-title bg-primary text-primary rounded-circle fs-4">
                                            <i class="ri-hand-coin-fill text-light align-middle"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 fs-14"><span class="text-body">Cash Advances</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-12">Total cash advance records</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <p class="mb-0 mt-2 text-primary text-center fw-semibold fs-16">{{ cashAdvanceBreakdown.total }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-white shadow-none mb-0" style="height: 190px; overflow: auto;">
                            <ul class="list-group list-group-flush border-dashed mb-0 mt-0 p-2">
                                <li class="list-group-item" v-for="(item,index) in cashAdvanceStatusList" v-bind:key="index">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 avatar-xs">
                                            <span class="avatar-title rounded-circle" :class="'bg-'+item.color+'-subtle text-'+item.color">
                                                <i class="ri-checkbox-circle-fill"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0 fs-13">{{ item.name }}</h6>
                                            <p class="text-muted mb-0 fs-11">{{ item.description }}</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <h5 class="mb-0 fs-14">{{ item.count }}</h5>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Recent Expenses -->
                <div class="col-md-6">
                    <div class="card bg-light-subtle shadow-none border mb-0">
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
                                    <h5 class="mb-0 fs-14"><span class="text-body">Recent Expenses</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-12">Latest recorded expenses across trips</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body bg-white rounded-bottom">
                            <simplebar data-simplebar style="height: 240px;">
                                <ul class="list-group list-group-flush border-dashed mb-n2 mt-n2" v-if="recentExpenses.length">
                                    <li class="list-group-item px-0" v-for="expense in recentExpenses" :key="expense.id">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h6 class="mb-0 fs-13">{{ expense.employee?.name ?? '-' }}</h6>
                                                <p class="text-muted mb-0 fs-11">
                                                    <span class="badge bg-light text-dark border">{{ expense.category?.name ?? '-' }}</span>
                                                    {{ expense.trip?.code ?? '' }}
                                                </p>
                                            </div>
                                            <span class="fw-semibold fs-13">{{ formatCurrency(expense.amount) }}</span>
                                        </div>
                                    </li>
                                </ul>
                                <p v-else class="text-muted text-center mb-0">No expenses recorded yet.</p>
                            </simplebar>
                        </div>
                    </div>
                </div>

                <!-- Recent Sales -->
                <div class="col-md-6">
                    <div class="card bg-light-subtle shadow-none border mb-0">
                        <div class="card-header bg-light-subtle">
                            <div class="d-flex mb-n3">
                                <div class="flex-shrink-0 me-3">
                                    <div style="height:2.5rem;width:2.5rem;">
                                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                            <i class="ri-shopping-bag-fill text-primary fs-24"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 fs-14"><span class="text-body">Recent Sales</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-12">Latest catch sold to buyers</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body bg-white rounded-bottom">
                            <simplebar data-simplebar style="height: 240px;">
                                <ul class="list-group list-group-flush border-dashed mb-n2 mt-n2" v-if="recentSales.length">
                                    <li class="list-group-item px-0" v-for="sale in recentSales" :key="sale.id">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h6 class="mb-0 fs-13">{{ sale.buyer?.name ?? '-' }}</h6>
                                                <p class="text-muted mb-0 fs-11">{{ sale.trip?.code ?? '' }}</p>
                                            </div>
                                            <span class="fw-semibold fs-13">{{ formatCurrency(sale.total) }}</span>
                                        </div>
                                    </li>
                                </ul>
                                <p v-else class="text-muted text-center mb-0">No sales recorded yet.</p>
                            </simplebar>
                        </div>
                    </div>
                </div>
            </BRow>
        </b-col>

        <!-- Money totals -->
        <b-col lg="3" class="mt-n2">
            <BRow class="g-2">
                <b-col lg="12">
                    <b-card no-body class="bg-success-subtle border shadow-none" style="height: 76px;">
                        <b-card-body class="d-flex align-items-center h-100">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light text-success rounded-circle fs-2">
                                    <i class="ri-shopping-bag-fill"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-semibold fs-12 text-muted mb-0">Total Sales</p>
                                <h2 class="fs-18 mb-0 text-success">{{ formatCurrency(stats.sales) }}</h2>
                                <p class="fs-11 text-muted mb-0">Catch sold to buyers</p>
                            </div>
                        </b-card-body>
                    </b-card>
                </b-col>
                <b-col lg="12" class="mt-n2">
                    <b-card no-body class="bg-warning-subtle border shadow-none" style="height: 76px;">
                        <b-card-body class="d-flex align-items-center h-100">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light text-warning rounded-circle fs-2">
                                    <i class="ri-hand-coin-fill"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-semibold fs-12 text-muted mb-0">Cash Advances</p>
                                <h2 class="fs-18 mb-0 text-warning">{{ formatCurrency(stats.cashAdvanceUnpaid) }}</h2>
                                <p class="fs-11 text-muted mb-0">Outstanding balance</p>
                            </div>
                        </b-card-body>
                    </b-card>
                </b-col>
                <b-col lg="12" class="mt-n2">
                    <b-card no-body class="bg-danger-subtle border shadow-none" style="height: 76px;">
                        <b-card-body class="d-flex align-items-center h-100">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light text-danger rounded-circle fs-2">
                                    <i class="ri-file-list-3-fill"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-semibold fs-12 text-muted mb-0">Total Expenses</p>
                                <h2 class="fs-18 mb-0 text-danger">{{ formatCurrency(stats.expenses) }}</h2>
                                <p class="fs-11 text-muted mb-0">All recorded expenses</p>
                            </div>
                        </b-card-body>
                    </b-card>
                </b-col>
                
            </BRow>
        </b-col>
    </BRow>
</template>
<script>
import simplebar from "simplebar-vue";
import PageHeader from '@/Shared/Components/PageHeader.vue';

export default {
    components: { PageHeader, simplebar },
    props: {
        activeTrip: { type: Object, default: null },
        stats: {
            type: Object,
            default: () => ({ trips: 0, expenses: 0, sales: 0, cashAdvanceUnpaid: 0 }),
        },
        tripBreakdown: {
            type: Object,
            default: () => ({ preparing: 0, ongoing: 0, completed: 0 }),
        },
        salesBreakdown: {
            type: Object,
            default: () => ({ total: 0, pending: 0, pendingPayment: 0, paid: 0 }),
        },
        cashAdvanceBreakdown: {
            type: Object,
            default: () => ({ total: 0, paid: 0, unpaid: 0 }),
        },
        recentExpenses: { type: Array, default: () => [] },
        recentSales: { type: Array, default: () => [] },
    },
    data(){
        return {
            month: null,
            year: null,
            months: [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ],
            years: Array.from({ length: 10 }, (_, i) => new Date().getFullYear() - i),
        };
    },
    computed: {
        firstName() {
            return this.$page.props.user.data.name?.split(' ')[0] ?? '';
        },
        carrierCount() {
            return this.activeTrip?.carriers?.length ?? 0;
        },
        tubCount() {
            return this.activeTrip?.carriers?.reduce((total, carrier) => total + (carrier.tubs?.length ?? 0), 0) ?? 0;
        },
        tripStatusList() {
            return [
                { name: 'Preparing', description: 'Not yet departed', count: this.tripBreakdown.preparing, color: 'secondary' },
                { name: 'Ongoing', description: 'Departed, not yet completed', count: this.tripBreakdown.ongoing, color: 'warning' },
                { name: 'Completed', description: 'Trip fully completed', count: this.tripBreakdown.completed, color: 'success' },
            ];
        },
        salesStatusList() {
            return [
                { name: 'Pending', description: 'No buyer assigned yet', count: this.salesBreakdown.pending, color: 'secondary' },
                { name: 'Pending Payment', description: 'Buyer assigned, awaiting payment', count: this.salesBreakdown.pendingPayment, color: 'warning' },
                { name: 'Paid', description: 'Payment received', count: this.salesBreakdown.paid, color: 'success' },
            ];
        },
        cashAdvanceStatusList() {
            return [
                { name: 'Paid', description: 'Cash advance settled', count: this.cashAdvanceBreakdown.paid, color: 'success' },
                { name: 'Unpaid', description: 'Cash advance outstanding', count: this.cashAdvanceBreakdown.unpaid, color: 'warning' },
            ];
        },
    },
    methods: {
        formatCurrency(value) {
            return '₱' + Number(value ?? 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        setDefaultImage(event) {
            event.target.src = '/images/avatars/avatar.jpg';
        },
    },
}
</script>

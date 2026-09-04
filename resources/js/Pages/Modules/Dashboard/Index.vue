<template>
    <PageHeader title="Dashboard" pageTitle="NMS" />

    <BRow class="g-3">
        <div class="col-md-12">
            <b-card no-body class="bg-white-subtle border shadow-none">
                <b-card-body>
                    <div class="d-flex align-items-center mb-n2">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-17">
                                    <img class="rounded-circle header-profile-user" :src="$page.props.user.data.avatar" @error="setDefaultImage($event)" :alt="$page.props.user.data.username">
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 mt-2 fs-14 fw-semibold">Welcome back, {{ firstName }}</h5>
                            <p class="text-muted fs-12 mb-0">Here's a summary of your boat trip operations.</p>
                        </div>
                    </div>
                </b-card-body>
            </b-card>
        </div>

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
                        <Link href="/trips" class="btn btn-sm btn-primary">View Trip</Link>
                    </div>
                </div>
                <div class="card-body text-center py-4" v-else>
                    <p class="text-muted fs-13 mb-2">No active trip right now.</p>
                    <Link href="/trips" class="btn btn-sm btn-primary">Start a New Trip</Link>
                </div>
            </div>
        </div>

        <!-- Stat cards -->
        <div class="col-md-3">
            <Link href="/trips" class="text-decoration-none">
                <div class="card border shadow-none">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                                    <i class="ri-sailboat-fill"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0 fs-18">{{ stats.trips }}</h4>
                            <p class="text-muted fs-12 mb-0">Total Trips</p>
                        </div>
                    </div>
                </div>
            </Link>
        </div>
        <div class="col-md-3">
            <Link href="/expenses" class="text-decoration-none">
                <div class="card border shadow-none">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-danger-subtle text-danger rounded-circle fs-18">
                                    <i class="ri-file-list-3-fill"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0 fs-18">{{ formatCurrency(stats.expenses) }}</h4>
                            <p class="text-muted fs-12 mb-0">Total Expenses</p>
                        </div>
                    </div>
                </div>
            </Link>
        </div>
        <div class="col-md-3">
            <Link href="/sales" class="text-decoration-none">
                <div class="card border shadow-none">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle fs-18">
                                    <i class="ri-shopping-bag-fill"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0 fs-18">{{ formatCurrency(stats.sales) }}</h4>
                            <p class="text-muted fs-12 mb-0">Total Sales</p>
                        </div>
                    </div>
                </div>
            </Link>
        </div>
        <div class="col-md-3">
            <Link href="/cash-advances" class="text-decoration-none">
                <div class="card border shadow-none">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-18">
                                    <i class="ri-hand-coin-fill"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0 fs-18">{{ formatCurrency(stats.cashAdvanceUnpaid) }}</h4>
                            <p class="text-muted fs-12 mb-0">Unpaid Cash Advances</p>
                        </div>
                    </div>
                </div>
            </Link>
        </div>

        <!-- Recent Expenses -->
        <div class="col-md-6">
            <div class="card border shadow-none">
                <div class="card-header bg-light-subtle d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fs-13">Recent Expenses</h5>
                    <Link href="/expenses" class="fs-12">View all</Link>
                </div>
                <div class="card-body p-0">
                    <div v-if="!recentExpenses.length" class="text-center text-muted fs-12 py-4">No expenses recorded yet.</div>
                    <ul v-else class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center justify-content-between" v-for="expense in recentExpenses" :key="expense.id">
                            <div>
                                <h6 class="mb-0 fs-13">{{ expense.name?.name ?? '-' }}</h6>
                                <p class="text-muted mb-0 fs-11">
                                    <span class="badge bg-light text-dark border">{{ expense.category?.name ?? '-' }}</span>
                                    {{ expense.trip?.code ?? '' }}
                                </p>
                            </div>
                            <span class="fw-semibold fs-13">{{ formatCurrency(expense.amount) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="col-md-6">
            <div class="card border shadow-none">
                <div class="card-header bg-light-subtle d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fs-13">Recent Sales</h5>
                    <Link href="/sales" class="fs-12">View all</Link>
                </div>
                <div class="card-body p-0">
                    <div v-if="!recentSales.length" class="text-center text-muted fs-12 py-4">No sales recorded yet.</div>
                    <ul v-else class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center justify-content-between" v-for="sale in recentSales" :key="sale.id">
                            <div>
                                <h6 class="mb-0 fs-13">{{ sale.buyer?.name ?? '-' }}</h6>
                                <p class="text-muted mb-0 fs-11">{{ sale.trip?.code ?? '' }}</p>
                            </div>
                            <span class="fw-semibold fs-13">{{ formatCurrency(sale.total) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Cash advance breakdown -->
        <div class="col-md-12">
            <div class="card border shadow-none">
                <div class="card-header bg-light-subtle d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fs-13">Cash Advances</h5>
                    <Link href="/cash-advances" class="fs-12">View all</Link>
                </div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-6">
                            <h4 class="mb-0 fs-18 text-success">{{ cashAdvanceBreakdown.paid }}</h4>
                            <p class="text-muted fs-12 mb-0">Paid</p>
                        </div>
                        <div class="col-6">
                            <h4 class="mb-0 fs-18 text-danger">{{ cashAdvanceBreakdown.unpaid }}</h4>
                            <p class="text-muted fs-12 mb-0">Unpaid</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BRow>
</template>
<script>
import PageHeader from '@/Shared/Components/PageHeader.vue';

export default {
    components: { PageHeader },
    props: {
        activeTrip: { type: Object, default: null },
        stats: {
            type: Object,
            default: () => ({ trips: 0, expenses: 0, sales: 0, cashAdvanceUnpaid: 0 }),
        },
        recentExpenses: { type: Array, default: () => [] },
        recentSales: { type: Array, default: () => [] },
        cashAdvanceBreakdown: {
            type: Object,
            default: () => ({ paid: 0, unpaid: 0 }),
        },
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

<template>
    <div class="card bg-light-subtle shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-ship-2-fill text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">Trip Details</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">Financial summary for this trip</p>
                </div>
            </div>
        </div>
        <div class="card-body bg-white rounded-bottom">
            <BRow class="g-2">
                <b-col lg="12">
                    <b-card no-body class="bg-success-subtle border shadow-none" style="height: 70px;">
                        <b-card-body class="d-flex align-items-center h-100">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light text-success rounded-circle fs-4">
                                    <i class="ri-shopping-bag-fill"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-semibold fs-11 text-muted mb-0">Total Sales</p>
                                <h2 class="fs-14 mb-0 text-success">{{ formatCurrency(totalSales) }}</h2>
                                <p class="fs-11 text-muted mb-0">Catch sold to buyers</p>
                            </div>
                        </b-card-body>
                    </b-card>
                </b-col>
                <b-col lg="12" class="mt-n3">
                    <b-card no-body class="bg-warning-subtle border shadow-none" style="height: 70px;">
                        <b-card-body class="d-flex align-items-center h-100">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light text-warning rounded-circle fs-4">
                                    <i class="ri-hand-coin-fill"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-semibold fs-11 text-muted mb-0">Cash Advances</p>
                                <h2 class="fs-14 mb-0 text-warning">{{ formatCurrency(totalCashAdvance) }}</h2>
                                <p class="fs-11 text-muted mb-0">Advanced to crew for this trip</p>
                            </div>
                        </b-card-body>
                    </b-card>
                </b-col>
                <b-col lg="12" class="mt-n3">
                    <b-card no-body class="bg-danger-subtle border shadow-none" style="height: 70px;">
                        <b-card-body class="d-flex align-items-center h-100">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light text-danger rounded-circle fs-4">
                                    <i class="ri-file-list-3-fill"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-semibold fs-11 text-muted mb-0">Total Expenses</p>
                                <h2 class="fs-14 mb-0 text-danger">{{ formatCurrency(totalExpenses) }}</h2>
                                <p class="fs-11 text-muted mb-0">All recorded expenses</p>
                            </div>
                        </b-card-body>
                    </b-card>
                </b-col>
            </BRow>
        </div>
    </div>
</template>
<script>
export default {
    props: ['trip'],
    computed: {
        totalSales(){
            return (this.trip.sales || []).reduce((sum, item) => sum + Number(item.total || 0), 0);
        },
        totalExpenses(){
            return (this.trip.expenses || []).reduce((sum, item) => sum + Number(item.amount || 0), 0);
        },
        totalCashAdvance(){
            return (this.trip.loans || []).reduce((sum, item) => sum + Number(item.amount || 0), 0);
        }
    },
    methods: {
        formatCurrency(value){
            return '₱' + Number(value ?? 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }
}
</script>

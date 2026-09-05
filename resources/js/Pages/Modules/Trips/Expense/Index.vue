<template>
    <Head title="Expenses"/>
    <PageHeader title="Expenses" pageTitle="Boat Operations" />
    <BRow class="g-3">
        <b-col lg="4">
            <b-card no-body class="bg-danger-subtle border shadow-none" style="height: 76px;">
                <b-card-body class="d-flex align-items-center h-100">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-light text-danger rounded-circle fs-2">
                            <i class="ri-file-list-3-fill"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-semibold fs-12 text-muted mb-0">Total Expenses</p>
                        <h2 class="fs-18 mb-0 text-danger">{{ formatCurrency(liveStats.total) }}</h2>
                        <p class="fs-11 text-muted mb-0">All recorded expenses</p>
                    </div>
                </b-card-body>
            </b-card>
        </b-col>
        <b-col lg="4">
            <b-card no-body class="bg-info-subtle border shadow-none" style="height: 76px;">
                <b-card-body class="d-flex align-items-center h-100">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-light text-info rounded-circle fs-2">
                            <i class="ri-calendar-check-fill"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-semibold fs-12 text-muted mb-0">{{ filter.month ? months[filter.month - 1] : 'Monthly' }} Expenses</p>
                        <h2 class="fs-18 mb-0 text-info">{{ formatCurrency(liveStats.monthly) }}</h2>
                        <p class="fs-11 text-muted mb-0">{{ filter.month ? months[filter.month - 1] : "This month's" }} expenses</p>
                    </div>
                </b-card-body>
            </b-card>
        </b-col>
        <b-col lg="4">
            <b-card no-body class="bg-light-subtle border shadow-none" style="height: 76px;">
                <b-card-body class="d-flex align-items-center h-100">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-light text-primary rounded-circle fs-2">
                            <i class="ri-calendar-2-line"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3 d-flex align-items-center gap-2">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-semibold fs-11 text-muted mb-1">Filter by Month</p>
                            <select v-model="filter.month" class="form-select form-select-sm">
                                <option :value="null">All Months</option>
                                <option v-for="(name, index) in months" v-bind:key="index" :value="index + 1">{{ name }}</option>
                            </select>
                        </div>
                        <b-button size="sm" variant="soft-secondary" @click="printExpenses" title="Print expenses for the selected month (or all months)">
                            <i class="ri-printer-fill"></i>
                        </b-button>
                        <b-button size="sm" :variant="showAnalytics ? 'primary' : 'soft-secondary'" @click="toggleAnalytics" title="Toggle monthly analytics view">
                            <i :class="showAnalytics ? 'ri-table-2' : 'ri-bar-chart-fill'"></i>
                        </b-button>
                    </div>
                </b-card-body>
            </b-card>
        </b-col>
    </BRow>
    <BRow>
        <div class="col-md-12 mt-n2">
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
                            <h5 class="mb-0 fs-14"><span class="text-body">List of Expenses</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">Hull Boat and Carrier expenses across all trips</p>
                        </div>
                    </div>
                </div>
                <div class="car-body bg-white border-bottom shadow-none" v-if="!showAnalytics">
                    <b-row class="mb-2 ms-1 me-1" style="margin-top: 12px;">
                        <b-col lg>
                            <div class="input-group mb-1">
                                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                                <input type="text" v-model="filter.keyword" placeholder="Search payee" class="form-control">
                                <div class="position-relative" style="max-width: 200px;">
                                    <input type="text" v-model="filter.trip_keyword" placeholder="Search trip code" class="form-control" @focus="showTripSuggestions = true" @blur="hideTripSuggestions">
                                    <ul class="list-group position-absolute w-100 shadow-sm fs-12" style="z-index: 1055; max-height: 220px; overflow-y: auto;" v-if="showTripSuggestions && tripSuggestions.length">
                                        <li class="list-group-item list-group-item-action" style="cursor: pointer;" v-for="trip in tripSuggestions" v-bind:key="trip.id" @mousedown.prevent="selectTrip(trip)">
                                            {{ trip.code }} - {{ trip.date }}
                                        </li>
                                    </ul>
                                </div>
                                <input type="date" v-model="filter.date" class="form-control" style="max-width: 180px;" title="Date">
                                <Multiselect class="white" style="width: 15%;" :options="categoryOptions" v-model="filter.category" label="name" :searchable="true" placeholder="All Categories" />
                                <b-button type="button" variant="primary" @click="openCreate">
                                    <i class="ri-add-circle-fill align-bottom me-1"></i> New Expense
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                </div>
                <div class="card-body bg-white rounded-bottom p-2" v-if="showAnalytics" style="height: calc(100vh - 390px);">
                    <div ref="analyticsWrapper" style="height: 100%;">
                        <apexchart class="apex-charts" type="bar" dir="ltr" :height="analyticsHeight" :series="analyticsSeries" :options="analyticsOptions"></apexchart>
                    </div>
                </div>
                <template v-else>
                    <div class="card-body bg-white rounded-bottom">
                        <div class="table-responsive table-card" style="height: calc(100vh - 510px); overflow: auto;">
                            <table class="table align-middle table-striped table-centered mb-0">
                                <thead class="table-light thead-fixed">
                                    <tr class="fs-11">
                                        <th>Payee</th>
                                        <th>Category</th>
                                        <th>Trip</th>
                                        <th class="text-end">Amount</th>
                                        <th>Date</th>
                                        <th style="width: 6%;"></th>
                                    </tr>
                                </thead>
                                <tbody class="table-white fs-12">
                                    <tr v-for="(list,index) in lists" v-bind:key="index">
                                        <td>{{ list.employee ? list.employee.name : '-' }}</td>
                                        <td><span class="badge bg-info-subtle text-info">{{ list.category ? list.category.name : '-' }}</span></td>
                                        <td>{{ list.trip ? list.trip.code : '-' }}</td>
                                        <td class="text-end">{{ formatCurrency(list.amount) }}</td>
                                        <td>{{ list.created_at }}</td>
                                        <td class="text-end">
                                            <a @click="openUpdate(list)" class="btn btn-ghost-primary btn-icon btn-sm" role="button">
                                                <i class="ri-edit-2-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr v-if="!lists.length">
                                        <td colspan="6" class="text-center text-muted">No expenses found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <Pagination class="ms-2 me-2 mt-n1" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
                    </div>
                </template>
            </div>
        </div>
    </BRow>
    <Create :categories="categories" :names="names" ref="create" @update="fetch"/>
</template>
<script>
import _ from 'lodash';
import Create from './Modals/Create.vue';
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { PageHeader, Pagination, Multiselect, Create },
    props: {
        categories: { type: Array, default: () => [] },
        names: { type: Object, default: () => ({}) },
        stats: {
            type: Object,
            default: () => ({ total: 0, monthly: 0 }),
        }
    },
    data(){
        return {
            lists: [],
            meta: {},
            links: {},
            liveStats: { ...this.stats },
            tripSuggestions: [],
            showTripSuggestions: false,
            months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            filter: {
                keyword: null,
                category: null,
                date: null,
                trip_keyword: null,
                month: null
            },
            showAnalytics: false,
            analyticsHeight: 400,
            analyticsSeries: [],
            analyticsOptions: {
                chart: { toolbar: { show: false } },
                xaxis: {
                    categories: [],
                    axisTicks: { show: false },
                    axisBorder: { show: false },
                },
                grid: {
                    show: true,
                    xaxis: { lines: { show: true } },
                    yaxis: { lines: { show: false } },
                    padding: { top: 25, right: -2, bottom: 15, left: 10 },
                },
                legend: {
                    show: true,
                    horizontalAlign: 'center',
                    markers: { width: 9, height: 9, radius: 6 },
                    itemMargin: { horizontal: 10, vertical: 0 },
                },
                dataLabels: {
                    enabled: true,
                    formatter: (value) => Number(value ?? 0).toLocaleString('en-PH'),
                    style: { fontSize: '8px' },
                    offsetY: -8,
                },
                plotOptions: {
                    bar: { columnWidth: '40%', barHeight: '70%', minBarHeight: 3, hideOverflowingLabels: true },
                },
                colors: ['#f06548'],
                yaxis: {
                    labels: {
                        formatter: (value) => '₱' + Number(value ?? 0).toLocaleString('en-PH'),
                    },
                },
            }
        }
    },
    computed: {
        categoryOptions(){
            if(this.categories && this.categories.length){
                return this.categories;
            }
            return [ { value: 'Hull Boat', name: 'Hull Boat' }, { value: 'Carrier', name: 'Carrier' } ];
        }
    },
    created(){
        this.fetch();
    },
    beforeUnmount(){
        window.removeEventListener('resize', this.measureAnalyticsHeight);
    },
    watch: {
        "filter.keyword"(){
            this.checkSearchStr();
        },
        "filter.category"(){
            this.fetch();
        },
        "filter.date"(){
            this.fetch();
        },
        "filter.trip_keyword"(){
            this.checkTripSearchStr();
        },
        "filter.month"(){
            this.fetch();
            this.fetchStats();
        }
    },
    methods: {
        checkSearchStr: _.debounce(function(){
            this.fetch();
        }, 300),
        checkTripSearchStr: _.debounce(function(){
            this.fetch();
            this.fetchTripSuggestions();
        }, 300),
        fetchTripSuggestions(){
            if(!this.filter.trip_keyword){
                this.tripSuggestions = [];
                return;
            }
            axios.get('/trips', { params: { options: 'lists', keyword: this.filter.trip_keyword, counts: 5 } })
            .then(response => {
                this.tripSuggestions = response.data.data;
            })
            .catch(err => console.log(err));
        },
        selectTrip(trip){
            this.filter.trip_keyword = trip.code;
            this.tripSuggestions = [];
            this.showTripSuggestions = false;
        },
        hideTripSuggestions(){
            setTimeout(() => { this.showTripSuggestions = false; }, 150);
        },
        fetch(page_url){
            page_url = page_url || '/expenses';
            axios.get(page_url, {
                params: {
                    options: 'lists',
                    keyword: this.filter.keyword,
                    category_id: this.filter.category,
                    date: this.filter.date,
                    trip_keyword: this.filter.trip_keyword,
                    month: this.filter.month,
                    counts: 10
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;
                }
            })
            .catch(err => console.log(err));
        },
        fetchStats(){
            axios.get('/expenses', { params: { options: 'stats', month: this.filter.month } })
            .then(response => {
                this.liveStats = response.data;
            })
            .catch(err => console.log(err));
        },
        toggleAnalytics(){
            this.showAnalytics = !this.showAnalytics;
            if(this.showAnalytics){
                this.fetchAnalytics();
                window.addEventListener('resize', this.measureAnalyticsHeight);
            } else {
                window.removeEventListener('resize', this.measureAnalyticsHeight);
            }
        },
        measureAnalyticsHeight(){
            if(this.$refs.analyticsWrapper){
                this.analyticsHeight = this.$refs.analyticsWrapper.clientHeight;
            }
        },
        fetchAnalytics(){
            axios.get('/expenses', { params: { options: 'analytics' } })
            .then(response => {
                this.analyticsOptions = {
                    ...this.analyticsOptions,
                    xaxis: { ...this.analyticsOptions.xaxis, categories: response.data.categories },
                };
                this.analyticsSeries = [
                    { name: 'Monthly Expenses', data: response.data.expenses },
                ];
                this.$nextTick(() => {
                    this.measureAnalyticsHeight();
                    setTimeout(() => window.dispatchEvent(new Event('resize')), 100);
                });
            })
            .catch(err => console.log(err));
        },
        openCreate(){
            this.$refs.create.show();
        },
        openUpdate(data){
            this.$refs.create.edit(data);
        },
        printExpenses(){
            const params = new URLSearchParams();
            if(this.filter.month){
                params.set('month', this.filter.month);
            }
            if(this.filter.trip_keyword){
                params.set('trip_keyword', this.filter.trip_keyword);
            }
            window.open(`/expenses/print?${params.toString()}`, '_blank');
        },
        formatCurrency(value){
            return '₱' + Number(value ?? 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }
}
</script>

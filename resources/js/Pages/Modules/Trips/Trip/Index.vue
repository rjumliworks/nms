<template>
    <Head title="Trips"/>
    <PageHeader title="Boat Trips" pageTitle="Boat Operations" />
    <BRow class="g-3">
        <b-col lg="3">
            <b-card no-body class="bg-primary-subtle border shadow-none" style="height: 76px;">
                <b-card-body class="d-flex align-items-center h-100">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-light text-primary rounded-circle fs-2">
                            <i class="ri-sailboat-fill"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-semibold fs-12 text-muted mb-0">Total Trips</p>
                        <h2 class="fs-18 mb-0 text-primary">{{ liveStats.total }}</h2>
                        <p class="fs-11 text-muted mb-0">All recorded trips</p>
                    </div>
                </b-card-body>
            </b-card>
        </b-col>
        <b-col lg="3">
            <b-card no-body class="bg-warning-subtle border shadow-none" style="height: 76px;">
                <b-card-body class="d-flex align-items-center h-100">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-light text-warning rounded-circle fs-2">
                            <i class="ri-navigation-fill"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-semibold fs-12 text-muted mb-0">Ongoing</p>
                        <h2 class="fs-18 mb-0 text-warning">{{ liveStats.ongoing }}</h2>
                        <p class="fs-11 text-muted mb-0">{{ filter.month ? months[filter.month - 1] : "This month's" }} departed trips</p>
                    </div>
                </b-card-body>
            </b-card>
        </b-col>
        <b-col lg="3">
            <b-card no-body class="bg-success-subtle border shadow-none" style="height: 76px;">
                <b-card-body class="d-flex align-items-center h-100">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-light text-success rounded-circle fs-2">
                            <i class="ri-checkbox-circle-fill"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-semibold fs-12 text-muted mb-0">Completed</p>
                        <h2 class="fs-18 mb-0 text-success">{{ liveStats.completed }}</h2>
                        <p class="fs-11 text-muted mb-0">{{ filter.month ? months[filter.month - 1] : "This month's" }} completed trips</p>
                    </div>
                </b-card-body>
            </b-card>
        </b-col>
        <b-col lg="3">
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
                        <b-button size="sm" variant="soft-secondary" @click="printTrips" title="Print trips for the selected month (or all months)">
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
                                    <i class="ri-sailboat-fill text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">List of Trips</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">Boat trips, their carriers, and catch handling</p>
                        </div>
                    </div>
                </div>
                <div class="car-body bg-white border-bottom shadow-none" v-if="!showAnalytics">
                    <b-row class="mb-2 ms-1 me-1" style="margin-top: 12px;">
                        <b-col lg>
                            <div class="input-group mb-1">
                                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                                <input type="text" v-model="filter.keyword" placeholder="Search trip code" class="form-control">
                                <input type="date" v-model="filter.date" class="form-control" style="max-width: 180px;" title="Date">
                                <Multiselect class="white" style="width: 15%;" :options="statusOptions" v-model="filter.status" label="name" :searchable="true" :can-clear="false" placeholder="All Statuses" />
                                <b-button type="button" variant="primary" @click="openCreate">
                                    <i class="ri-add-circle-fill align-bottom me-1"></i> New Trip
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
                                        <th>Code</th>
                                        <th>Date</th>
                                        <th>Boat</th>
                                        <th class="text-center">Carriers</th>
                                        <th class="text-center">Status</th>
                                        <th style="width: 6%;"></th>
                                    </tr>
                                </thead>
                                <tbody class="table-white fs-12">
                                    <tr v-for="(list,index) in lists" v-bind:key="index">
                                        <td class="fw-semibold">{{ list.code }}</td>
                                        <td>{{ formatLongDate(list.date) }}</td>
                                        <td>{{ list.boat ? list.boat.name : '-' }}</td>
                                        <td class="text-center">{{ list.carriers ? list.carriers.length : 0 }}</td>
                                        <td class="text-center">
                                            <span v-if="list.is_completed" class="badge bg-success">Completed</span>
                                            <span v-else-if="list.has_departed" class="badge bg-warning">Ongoing</span>
                                            <span v-else class="badge bg-secondary">Preparing</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <BDropdown variant="link" toggle-class="btn btn-light btn-sm dropdown" no-caret menu-class="dropdown-menu-end">
                                                    <template #button-content>
                                                        <i class="ri-more-fill"></i>
                                                    </template>
                                                    <li>
                                                        <Link :href="`/trips/${list.id}`" class="dropdown-item d-flex align-items-center" role="button">
                                                            <i class="ri-eye-fill me-2"></i> View
                                                        </Link>
                                                    </li>
                                                    <li>
                                                        <a @click="openUpdate(list)" class="dropdown-item d-flex align-items-center" role="button">
                                                            <i class="ri-edit-2-fill me-2"></i> Update
                                                        </a>
                                                    </li>
                                                </BDropdown>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="!lists.length">
                                        <td colspan="6" class="text-center text-muted">No trips found</td>
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
    <Create :boats="boats" ref="create" @update="fetch"/>
</template>
<script>
import _ from 'lodash';
import Create from './Modals/Create.vue';
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
import { formatLongDate } from '@/Shared/Utils/dateFormat.js';
export default {
    components: { PageHeader, Pagination, Create, Multiselect },
    props: {
        boats: { type: Array, default: () => [] },
        stats: {
            type: Object,
            default: () => ({ total: 0, ongoing: 0, completed: 0 }),
        }
    },
    data(){
        return {
            lists: [],
            meta: {},
            links: {},
            liveStats: { ...this.stats },
            statusOptions: [
                { value: null, name: 'All Statuses' },
                { value: 'preparing', name: 'Preparing' },
                { value: 'ongoing', name: 'Ongoing' },
                { value: 'completed', name: 'Completed' },
            ],
            months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            filter: {
                keyword: null,
                status: null,
                date: null,
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
                    bar: { columnWidth: '70%', barHeight: '70%', minBarHeight: 3, hideOverflowingLabels: true },
                },
                colors: ['#556ee6', '#f1b44c', '#34c38f'],
                yaxis: {
                    labels: {
                        formatter: (value) => Number(value ?? 0).toLocaleString('en-PH'),
                    },
                },
            }
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
        "filter.status"(){
            this.fetch();
        },
        "filter.date"(){
            this.fetch();
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
        fetch(page_url){
            page_url = page_url || '/trips';
            axios.get(page_url, {
                params: {
                    options: 'lists',
                    keyword: this.filter.keyword,
                    status: this.filter.status,
                    date: this.filter.date,
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
            axios.get('/trips', { params: { options: 'stats', month: this.filter.month } })
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
            axios.get('/trips', { params: { options: 'analytics' } })
            .then(response => {
                this.analyticsOptions = {
                    ...this.analyticsOptions,
                    xaxis: { ...this.analyticsOptions.xaxis, categories: response.data.categories },
                };
                this.analyticsSeries = [
                    { name: 'Total Trips', data: response.data.total },
                    { name: 'Ongoing', data: response.data.ongoing },
                    { name: 'Completed', data: response.data.completed },
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
        printTrips(){
            const params = new URLSearchParams();
            if(this.filter.month){
                params.set('month', this.filter.month);
            }
            window.open(`/trips/print?${params.toString()}`, '_blank');
        },
        formatLongDate
    }
}
</script>

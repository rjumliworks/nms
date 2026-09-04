<template>
    <Head title="Trips"/>
    <PageHeader title="Boat Trips" pageTitle="Boat Operations" />
    <BRow>
        <div class="col-md-12">
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
                <div class="car-body bg-white border-bottom shadow-none">
                    <b-row class="mb-2 ms-1 me-1" style="margin-top: 12px;">
                        <b-col lg>
                            <div class="input-group mb-1">
                                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                                <input type="text" v-model="filter.keyword" placeholder="Search trip code" class="form-control">
                                <b-button type="button" variant="primary" @click="openCreate">
                                    <i class="ri-add-circle-fill align-bottom me-1"></i> New Trip
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                </div>
                <div class="card-body bg-white rounded-bottom">
                    <div class="table-responsive table-card" style="height: calc(100vh - 465px); overflow: auto;">
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
                                    <td>{{ list.date }}</td>
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
                                                    <a @click="openView(list)" class="dropdown-item d-flex align-items-center" role="button">
                                                        <i class="ri-eye-fill me-2"></i> View
                                                    </a>
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
            </div>
        </div>
    </BRow>
    <Create :boats="boats" ref="create" @update="fetch"/>
    <View ref="view" @add-carrier="openCarrier"/>
    <Carrier :names="names" ref="carrier" @created="onCarrierCreated"/>
</template>
<script>
import _ from 'lodash';
import Create from './Modals/Create.vue';
import View from './Modals/View.vue';
import Carrier from './Modals/Carrier.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { PageHeader, Pagination, Create, View, Carrier },
    props: {
        boats: { type: Array, default: () => [] },
        names: { type: Object, default: () => ({}) }
    },
    data(){
        return {
            lists: [],
            meta: {},
            links: {},
            filter: {
                keyword: null
            }
        }
    },
    created(){
        this.fetch();
    },
    watch: {
        "filter.keyword"(){
            this.checkSearchStr();
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
        openCreate(){
            this.$refs.create.show();
        },
        openUpdate(data){
            this.$refs.create.edit(data);
        },
        openView(data){
            this.$refs.view.show(data);
        },
        openCarrier(trip){
            this.$refs.carrier.show(trip);
        },
        onCarrierCreated(){
            this.fetch();
            this.$refs.view.hide();
        }
    }
}
</script>

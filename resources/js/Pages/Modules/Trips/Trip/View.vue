<template>
    <Head :title="`Trip ${trip.code}`" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="w-100 p-4 pb-0" ref="box">
            <BRow>
                <BCol lg="12">
                    <BCard no-body class="mt-n4 mx-n4">
                        <div class="bg-info-subtle">
                            <BCardBody class="pb-0 px-4">
                                <BRow class="mb-3">
                                    <BCol md>
                                        <BRow class="align-items-center g-3">
                                            <BCol md="auto">
                                                <div class="avatar-title bg-white rounded-circle" style="height: 3.5rem; width: 3.5rem;">
                                                    <i class="ri-sailboat-fill fs-24 text-primary"></i>
                                                </div>
                                            </BCol>
                                            <BCol md>
                                                <div class="ms-2">
                                                    <h4 class="fw-bold text-uppercase">{{ trip.code }}</h4>
                                                    <div class="hstack gap-3 flex-wrap">
                                                        <div><span class="text-muted">Boat :</span> <span class="fw-medium">{{ trip.boat ? trip.boat.name : '-' }}</span></div>
                                                        <div class="vr" style="width: 1px;"></div>
                                                        <div><span class="text-muted">Date :</span> <span class="fw-medium">{{ trip.date }}</span></div>
                                                        <div class="vr" style="width: 1px;"></div>
                                                        <div>
                                                            <span v-if="trip.is_completed" class="badge bg-success">Completed</span>
                                                            <span v-else-if="trip.has_departed" class="badge bg-warning">Ongoing</span>
                                                            <span v-else class="badge bg-secondary">Preparing</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </BCol>
                                        </BRow>
                                    </BCol>
                                    <BCol md="auto">
                                        <div class="hstack gap-1 flex-wrap">
                                            <button @click="back()" type="button" class="btn py-0 fs-16 mt-3 text-body">
                                                <i class="ri-share-line"></i>
                                            </button>
                                        </div>
                                    </BCol>
                                </BRow>
                            </BCardBody>
                        </div>
                    </BCard>
                </BCol>
                <BCol lg="3">
                    <Sidebar :trip="trip" @add-carrier="openCarrier"/>
                </BCol>
                <BCol lg="9">
                    <Main :trip="trip" :names="names" :categories="categories"/>
                </BCol>
            </BRow>
        </div>
    </div>
    <Carrier :names="names" ref="carrier" @created="refresh"/>
</template>
<script>
import Main from './Components/Main.vue';
import Sidebar from './Components/Sidebar.vue';
import Carrier from './Modals/Carrier.vue';
export default {
    components: { Main, Sidebar, Carrier },
    props: ['trip_data','names','categories'],
    data(){
        return {
            trip: this.trip_data.data
        }
    },
    methods: {
        back(){
            this.$inertia.visit('/trips');
        },
        openCarrier(){
            this.$refs.carrier.show(this.trip);
        },
        refresh(){
            this.$inertia.reload({ only: ['trip_data'] });
        }
    }
}
</script>

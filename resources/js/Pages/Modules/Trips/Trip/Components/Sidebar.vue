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
                    <p class="text-muted text-truncate-two-lines fs-12">Carriers and catch handling for this trip</p>
                </div>
            </div>
        </div>
        <div class="card-body bg-white rounded-bottom border-bottom" v-if="trip.note">
            <p class="mb-0 text-primary fs-12 fw-semibold">Note</p>
            <p class="mb-0 fs-12">{{ trip.note }}</p>
        </div>
        <div class="card bg-white rounded-bottom shadow-none mb-0" style="height: calc(100vh - 398px); overflow: auto;">
            <div class="d-flex align-items-center justify-content-between p-3 pb-0">
                <p class="mb-0 text-primary fs-12 fw-semibold">Carriers</p>
                <b-button size="sm" variant="soft-primary" @click="$emit('add-carrier')" type="button">
                    <i class="ri-add-line align-bottom me-1"></i> New
                </b-button>
            </div>
            <ul class="mt-2 mb-1 p-3 pt-0">
                <li class="list-group-item px-0 border-0" v-for="(carrier,index) in trip.carriers" v-bind:key="index">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-truck-fill fs-14 text-secondary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 fs-11">{{ carrier.carrier ? carrier.carrier.name : '-' }}</h6>
                            <p class="fs-10 mb-0 text-muted">{{ carrier.tubs ? carrier.tubs.length : 0 }} tub(s) &middot; Total {{ carrier.total }}</p>
                        </div>
                    </div>
                </li>
                <li class="px-0 text-center text-muted fs-12" v-if="!trip.carriers || !trip.carriers.length">
                    No carriers yet
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    props: ['trip']
}
</script>

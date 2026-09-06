<template>
    <div class="table-responsive table-card" style="height: calc(100vh - 420px); overflow: auto;">
        <table class="table align-middle table-striped mb-0">
            <thead class="bg-primary text-white thead-fixed">
                <tr class="fs-11">
                    <th style="width: 8%;">#</th>
                    <th>Carrier</th>
                    <th class="text-center">Tubs</th>
                    <th>Species</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody class="fs-12">
                <tr v-for="(list,index) in trip.carriers" v-bind:key="index">
                    <td>{{ list.order }}</td>
                    <td>{{ list.carrier ? list.carrier.name : '-' }}</td>
                    <td class="text-center">{{ list.tubs ? list.tubs.length : 0 }}</td>
                    <td>{{ speciesFor(list) }}</td>
                    <td class="text-end">{{ formatCurrency(list.total) }}</td>
                </tr>
                <tr v-if="!trip.carriers || !trip.carriers.length">
                    <td colspan="5" class="text-center text-muted">No carriers recorded for this trip</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    props: ['trip'],
    methods: {
        speciesFor(carrier){
            const names = (carrier.tubs || [])
                .map(tub => tub.fish ? tub.fish.name : null)
                .filter(Boolean);
            return [...new Set(names)].join(', ') || '-';
        },
        formatCurrency(value){
            return '₱' + Number(value ?? 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }
}
</script>

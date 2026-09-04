<template>
    <Head title="Log in"/>
    <div id="layout-wrapper" class="auth-page-wrapper pt-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="auth-page-content">
            <BContainer>

                <BRow class="justify-content-center">
                    <BCol md="9" lg="7" xl="5">
                        <div class="card bg-light-subtle shadow-none border">
                            <div class="card-header bg-primary">
                                <div class="d-flex mb-n2">
                                    <div class="flex-shrink-0 me-3">
                                        <div style="height:2.5rem;width:2.5rem;">
                                        <img src="@assets/images/logo.png" alt="" class="avatar-sm">
                                        </div>
                                    </div>
                                     <div class="flex-grow-1">
                                        <h5 class="mb-0 mt-2 fs-14 fw-semibold text-uppercase text-white" style="font-size: 10.7px">NMS</h5>
                                        <p class="text-white fs-11">Fishing Management System</p>
                                    </div>
                                </div>
                            </div>
                            <div class="car-body bg-white shadow-none" style="padding: 33px;">
                                <form class="customform" @submit.prevent="submit">
                                    <div class="alert alert-warning alert-border-left alert-dismissible fade show material-shadow fs-11" role="alert">
                                        <span class="fs-10" style="line-height: 1.2; display: inline-block;"> <strong>Security Notice :</strong> This system is restricted to authorized personnel only. Unauthorized access is prohibited.</span>
                                    </div>
                                    <div class="mb-2">
                                        <label><i class="ri-mail-line"></i> Email Address</label>
                                        <div class="form-icon">
                                            <input type="email" v-model="form.email"
                                            class="form-control form-control-icon"
                                            id="iconInput" placeholder="Please enter your email address"
                                            :class="['form-control', form.errors.email && 'is-invalid']"
                                            @input="handleInput('email')"
                                            style="background-color: #f5f6f7;">
                                            <i class="ri-mail-fill text-muted" :class="(form.errors.email) ? 'mt-n3' : ''"></i>
                                            <InputError :message="form.errors.email" />
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label><i class="ri-key-2-fill"></i> Secure Password</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <div class="form-icon">
                                                <input :type="togglePassword ? 'text' : 'password'"
                                                v-model="form.password" class="form-control form-control-icon"
                                                id="password-input" placeholder="Please enter password"
                                                :class="['form-control', form.errors.password && 'is-invalid']"
                                                @input="handleInput('password')"
                                                style="background-color: #f5f6f7;">
                                                <i class="ri-lock-2-fill text-muted"></i>
                                                <InputError :message="form.errors.password" />
                                            </div>
                                            <BButton v-if="!form.errors.password" variant="link" class="position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="password-addon" @click="togglePassword = !togglePassword">
                                                <i class="ri-eye-fill align-middle"></i>
                                            </BButton>
                                        </div>
                                    </div>
                                    <div class="form-check mt-n1">
                                        <Checkbox v-model:checked="form.remember" name="remember" class="form-check-input" id="auth-remember-check" />
                                        <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                    </div>
                                    <div class="mt-3">
                                        <BButton variant="primary" class="w-100" type="submit" :class="['some-class', form.processing && 'opacity-25']" :disabled="form.processing">Sign In</BButton>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer bg-light-subtle">
                                 <div class="text-center p-1">
                                    <p class="mb-0 fs-11">Forgot your password?
                                        <Link href="/forgot-password" class="fw-semibold text-primary text-decoration-underline"> Click here</Link>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </BCol>
                </BRow>
            </BContainer>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Checkbox from '@/Shared/Components/Forms/Checkbox.vue';
import InputError from '@/Shared/Components/Forms/InputError.vue';
defineProps({
    canResetPassword: Boolean,
    status: String
});
const togglePassword = ref(false);
const form = useForm({
    email: '',
    password: '',
    remember: false,
});
const handleInput = (field) => {
    form.errors[field] = null; // or false
};
const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post('/login', {
        onError: (errors) => {
            console.log('Validation Errors:', errors);
        },
        onFinish: () => form.reset('password'),
    });
};
</script>
<script>
export default {
    layout: null,
}
</script>
<style scoped>
    input::placeholder {
        font-size: 12px;
        color: #999;
    }
</style>

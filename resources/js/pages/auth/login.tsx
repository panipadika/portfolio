import { Form, Head } from '@inertiajs/react';
import { ArrowRight, CheckCircle2, Lock, LogIn, Mail } from 'lucide-react';
import InputError from '@/components/input-error';
import PasskeyVerify from '@/components/passkey-verify';
import PasswordInput from '@/components/password-input';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

type Props = {
    status?: string;
    canResetPassword: boolean;
    canRegister?: boolean;
};

export default function Login({
    status,
    canResetPassword,
    canRegister = false,
}: Props) {
    return (
        <>
            <Head title="Admin Login" />

            {status && (
                <div className="mb-5 flex items-center gap-2.5 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-3.5 py-2.5 text-xs font-medium text-emerald-300 backdrop-blur-md">
                    <CheckCircle2 className="size-4 shrink-0 text-emerald-400" />
                    <span>{status}</span>
                </div>
            )}

            <PasskeyVerify
                label="Masuk dengan Passkey"
                loadingLabel="Memverifikasi Passkey..."
                separator="Atau masuk dengan email"
            />

            <Form
                {...store.form()}
                resetOnSuccess={['password']}
                className="flex flex-col gap-5"
            >
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-4">
                            <div className="grid gap-2">
                                <Label
                                    htmlFor="email"
                                    className="text-xs font-semibold tracking-wide text-slate-200"
                                >
                                    Email Administrator
                                </Label>
                                <div className="relative">
                                    <Mail
                                        className="pointer-events-none absolute top-1/2 left-3.5 size-4 -translate-y-1/2 text-slate-400"
                                        aria-hidden="true"
                                    />
                                    <Input
                                        id="email"
                                        type="email"
                                        name="email"
                                        required
                                        autoFocus
                                        tabIndex={1}
                                        autoComplete="email"
                                        placeholder="admin@panipadika.my.id"
                                        className="h-11 rounded-xl border-white/10 bg-slate-900/75 pl-10 text-sm text-white placeholder:text-slate-500 focus-visible:border-indigo-500 focus-visible:bg-slate-900 focus-visible:ring-indigo-500/25"
                                    />
                                </div>
                                <InputError
                                    message={errors.email}
                                    className="text-xs text-rose-400"
                                />
                            </div>

                            <div className="grid gap-2">
                                <div className="flex items-center justify-between gap-2">
                                    <Label
                                        htmlFor="password"
                                        className="text-xs font-semibold tracking-wide text-slate-200"
                                    >
                                        Password
                                    </Label>
                                    {canResetPassword && (
                                        <TextLink
                                            href={request()}
                                            className="ml-auto text-xs font-medium text-[#7185e8] no-underline transition-colors hover:text-cyan-300 hover:underline"
                                            tabIndex={5}
                                        >
                                            Lupa password?
                                        </TextLink>
                                    )}
                                </div>
                                <div className="relative">
                                    <Lock
                                        className="pointer-events-none absolute top-1/2 left-3.5 z-10 size-4 -translate-y-1/2 text-slate-400"
                                        aria-hidden="true"
                                    />
                                    <PasswordInput
                                        id="password"
                                        name="password"
                                        required
                                        tabIndex={2}
                                        autoComplete="current-password"
                                        placeholder="Masukkan password admin"
                                        className="h-11 rounded-xl border-white/10 bg-slate-900/75 pl-10 text-sm text-white placeholder:text-slate-500 focus-visible:border-indigo-500 focus-visible:bg-slate-900 focus-visible:ring-indigo-500/25"
                                    />
                                </div>
                                <InputError
                                    message={errors.password}
                                    className="text-xs text-rose-400"
                                />
                            </div>

                            <div className="flex items-center justify-between pt-0.5">
                                <div className="flex items-center gap-2.5">
                                    <Checkbox
                                        id="remember"
                                        name="remember"
                                        tabIndex={3}
                                        className="size-4 rounded-[5px] border-white/20 bg-slate-900/80 data-[state=checked]:border-indigo-500 data-[state=checked]:bg-indigo-600 data-[state=checked]:text-white"
                                    />
                                    <Label
                                        htmlFor="remember"
                                        className="cursor-pointer text-xs font-medium text-slate-300"
                                    >
                                        Ingat sesi saya
                                    </Label>
                                </div>
                            </div>

                            <Button
                                type="submit"
                                className="group mt-2 h-11 w-full cursor-pointer rounded-xl bg-gradient-to-r from-[#262f6b] via-indigo-600 to-[#4e63d9] text-sm font-semibold text-white shadow-[0_10px_30px_rgba(99,102,241,0.3)] transition-all duration-200 hover:from-[#313c87] hover:via-indigo-500 hover:to-[#7185e8] hover:shadow-[0_14px_34px_rgba(99,102,241,0.45)] active:scale-[0.99]"
                                tabIndex={4}
                                disabled={processing}
                                data-test="login-button"
                            >
                                {processing ? (
                                    <>
                                        <Spinner className="size-4" />
                                        Memverifikasi...
                                    </>
                                ) : (
                                    <>
                                        <LogIn className="size-4" />
                                        Masuk ke Dashboard
                                        <ArrowRight className="size-4 transition-transform duration-200 group-hover:translate-x-0.5" />
                                    </>
                                )}
                            </Button>
                        </div>

                        {canRegister && (
                            <div className="text-center text-xs text-slate-400">
                                Belum punya akun?{' '}
                                <TextLink
                                    href={register()}
                                    tabIndex={6}
                                    className="font-medium text-[#7185e8] hover:text-cyan-300"
                                >
                                    Daftar akun
                                </TextLink>
                            </div>
                        )}
                    </>
                )}
            </Form>
        </>
    );
}

Login.layout = {
    title: 'Masuk ke Admin Panel',
    description:
        'Kelola projek, pengalaman, sertifikat, dan pesan portofolio Anda.',
};

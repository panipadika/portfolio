import React from 'react';
import { Head } from '@inertiajs/react';
import PortfolioApp from '@/components/PortfolioApp';

export default function Welcome() {
    return (
        <>
            <Head>
                <title>
                    Pani Padika — Full-Stack Developer | 3D Interactive
                    Portfolio
                </title>
                <meta
                    name="description"
                    content="Portofolio resmi Pani Padika, Full-Stack Web Developer. Menampilkan interaksi 3D ID Card, showcase projek Laravel + React, sertifikat, dan formulir kontak langsung."
                />
                <meta name="theme-color" content="#0B0F17" />
            </Head>
            <PortfolioApp />
        </>
    );
}

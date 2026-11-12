import React from 'react';
import { Head } from '@inertiajs/react';
import PortfolioApp from '@/components/PortfolioApp';

export default function Welcome() {
    return (
        <>
            <Head>
                <title>Pani Padika — Portfolio</title>
                <meta
                    name="description"
                    content="Portofolio resmi Pani Padika, Full-Stack Web Developer."
                />
                <meta
                    name="keywords"
                    content="Pani Padika, Web Developer, Full-Stack Developer, Software Engineer, Laravel, React, Inertia.js, Three.js, Portfolio, Indonesia"
                />
                <meta name="author" content="Pani Padika" />
                <meta name="theme-color" content="#0B0F17" />

                {/* Open Graph / Facebook / WhatsApp / LinkedIn */}
                <meta property="og:type" content="website" />
                <meta property="og:site_name" content="Pani Padika Portfolio" />
                <meta
                    property="og:title"
                    content="Pani Padika — Full-Stack Developer"
                />
                <meta
                    property="og:description"
                    content="Portofolio resmi Pani Padika, Full-Stack Web Developer."
                />
                <meta property="og:image" content="/card-front.png" />
                <meta
                    property="og:image:alt"
                    content="Pani Padika — Full-Stack Developer 3D ID Card"
                />

                {/* Twitter / X */}
                <meta name="twitter:card" content="summary_large_image" />
                <meta
                    name="twitter:title"
                    content="Pani Padika — Full-Stack Developer"
                />
                <meta
                    name="twitter:description"
                    content="Portofolio resmi Pani Padika, Full-Stack Web Developer."
                />
                <meta name="twitter:image" content="/card-front.png" />
            </Head>
            <PortfolioApp />
        </>
    );
}

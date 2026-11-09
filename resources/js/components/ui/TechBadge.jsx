import React from 'react';
import BorderGlow from './BorderGlow';
import TechIcon from './TechIcon';

// Official SVG Brand Icons for Tech Badges
export const TECH_ICONS = {
    HTML: {
        color: '#E34F26',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#E34F26]">
                <path d="M1.5 0h21l-1.9 21.2L12 24l-8.6-2.8L1.5 0zm17.6 4.4H4.9l.4 4.5h13.4l-.4 4.5H9.6l.2 2.3 2.2.6 2.2-.6.3-3.4h2.7l-.4 5.3-4.8 1.4-4.8-1.4-.4-5.3h10.9l.7-7.9z" />
            </svg>
        ),
    },
    CSS: {
        color: '#1572B6',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#1572B6]">
                <path d="M1.5 0h21l-1.9 21.2L12 24l-8.6-2.8L1.5 0zm16.4 7.2H6.1l.4 4.5h9.4l-.4 4.5-3.5 1-3.5-1-.2-2.3H5.6l.4 4.5 6 1.7 6-1.7.9-10.7z" />
            </svg>
        ),
    },
    JavaScript: {
        color: '#F7DF1E',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#F7DF1E]">
                <path d="M0 0h24v24H0V0zm22.034 18.276c-.175-1.017-.888-1.798-2.176-2.316l-.822-.303c-.667-.249-.933-.509-.933-.896 0-.442.348-.737.942-.737.58 0 .918.257 1.157.697l1.792-1.144c-.588-1.07-1.542-1.637-2.949-1.637-1.79 0-2.981 1.054-2.981 2.65 0 1.251.748 1.996 2.051 2.477l.79.29c.772.285 1.109.617 1.109 1.082 0 .52-.469.877-1.189.877-.852 0-1.378-.403-1.696-1.042l-1.854 1.077c.614 1.258 1.761 1.942 3.55 1.942 2.022 0 3.328-1.026 3.328-2.659zM11.698 12.01h2.203v7.352c0 1.983-1.063 2.879-2.83 2.879-.693 0-1.386-.164-1.883-.393l.394-1.815c.348.15.753.256 1.139.256.741 0 1.18-.363 1.18-1.353V12.01z" />
            </svg>
        ),
    },
    Capacitor: {
        color: '#119EFF',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#119EFF]">
                <path d="M2.4 12l9.6-9.6L21.6 12 12 21.6 2.4 12zm3.4 0L12 18.2 18.2 12 12 5.8 5.8 12z" />
            </svg>
        ),
    },
    Firebase: {
        color: '#FFA000',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#FFA000]">
                <path d="M3.89 15.672L6.255.461A.542.542 0 0 1 7.27.283l3.543 6.64-6.923 8.749zm16.536 2.825l-2.09-13.11a.54.54 0 0 0-.916-.279L1.87 20.302l9.638 5.438a1.05 1.05 0 0 0 .984 0l7.934-7.243zM13.627 9.87l-2.18-4.175a.54.54 0 0 0-.955-.008L8.7 9.283l4.927.587z" />
            </svg>
        ),
    },
    VSCode: {
        color: '#007ACC',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#007ACC]">
                <path d="M23.15 2.587L18.21.21a1.49 1.49 0 0 0-1.705.29l-9.46 8.63-4.12-3.128a.999.999 0 0 0-1.276.057L.327 7.27a1 1 0 0 0 0 1.46l3.59 3.27-3.59 3.27a1 1 0 0 0 0 1.46l1.322 1.2a1 1 0 0 0 1.276.058l4.12-3.128 9.46 8.63a1.49 1.49 0 0 0 1.704.29l4.94-2.377A1.5 1.5 0 0 0 24 20.06V3.939a1.5 1.5 0 0 0-.85-1.352z" />
            </svg>
        ),
    },
    VScode: {
        color: '#007ACC',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#007ACC]">
                <path d="M23.15 2.587L18.21.21a1.49 1.49 0 0 0-1.705.29l-9.46 8.63-4.12-3.128a.999.999 0 0 0-1.276.057L.327 7.27a1 1 0 0 0 0 1.46l3.59 3.27-3.59 3.27a1 1 0 0 0 0 1.46l1.322 1.2a1 1 0 0 0 1.276.058l4.12-3.128 9.46 8.63a1.49 1.49 0 0 0 1.704.29l4.94-2.377A1.5 1.5 0 0 0 24 20.06V3.939a1.5 1.5 0 0 0-.85-1.352z" />
            </svg>
        ),
    },
    Gemini: {
        color: '#8E75FF',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#8E75FF]">
                <path d="M12 0C12 6.627 6.627 12 0 12c6.627 0 12 5.373 12 12 0-6.627 5.373-12 12-12-6.627 0-12-5.373-12-12z" />
            </svg>
        ),
    },
    Chromium: {
        color: '#2979FF',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#2979FF]">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8 0-.55.06-1.09.16-1.61L8.5 17.5V19c0 .55.45 1 1 1h2.5zm6.84-4.84l-2.43-4.21A4.98 4.98 0 0 0 12 9c-.58 0-1.13.11-1.65.29L6.17 4.77C7.79 3.66 9.8 3 12 3c4.28 0 7.87 2.92 8.84 6.88zM12 14a2 2 0 1 1 0-4 2 2 0 0 1 0 4z" />
            </svg>
        ),
    },
    'Android Studio': {
        color: '#3DDC84',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#3DDC84]">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
            </svg>
        ),
    },
    Vercel: {
        color: '#FFFFFF',
        icon: (
            <svg viewBox="0 0 24 24" className="h-3.5 w-3.5 shrink-0 fill-white">
                <path d="M12 1L24 22H0L12 1Z" />
            </svg>
        ),
    },
    React: {
        color: '#61DAFB',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#61DAFB]">
                <path d="M12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0-7c-5.5 0-9 2-9 4.5S6.5 11 12 11s9-2 9-4.5S17.5 2 12 2zm0 13c-5.5 0-9 2-9 4.5s3.5 4.5 9 4.5 9-2 9-4.5-3.5-4.5-9-4.5z" />
            </svg>
        ),
    },
    'Tailwind CSS': {
        color: '#38BDF8',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#38BDF8]">
                <path d="M12.001 4.8c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8.913.228 1.565.89 2.288 1.624C13.666 10.618 15.027 12 18.001 12c3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-.913-.228-1.565-.89-2.288-1.624C16.337 6.182 14.976 4.8 12.001 4.8zm-6 7.2c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8.913.228 1.565.89 2.288 1.624 1.177 1.194 2.538 2.576 5.512 2.576 3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-.913-.228-1.565-.89-2.288-1.624C10.337 13.382 8.976 12 6.001 12z" />
            </svg>
        ),
    },
    Laravel: {
        color: '#FF2D20',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#FF2D20]">
                <path d="M12 2L2 7l10 5 10-5-10-5zm0 9l-9-4.5V17l9 5 9-5v-5.5L12 11z" />
            </svg>
        ),
    },
    MySQL: {
        color: '#4479A1',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#4479A1]">
                <path d="M12 2C6.48 2 2 4.02 2 6.5v11C2 19.98 6.48 22 12 22s10-2.02 10-4.5v-11C22 4.02 17.52 2 12 2zm0 3c4.41 0 8 1.34 8 2.5S16.41 10 12 10 4 8.66 4 7.5 7.59 5 12 5z" />
            </svg>
        ),
    },
    PHP: {
        color: '#777BB4',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#777BB4]">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H8v-2h3v2zm4 0h-3v-2h3v2z" />
            </svg>
        ),
    },
    Python: {
        color: '#3776AB',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#3776AB]">
                <path d="M12 2C7.03 2 7.03 4.14 7.03 4.14v2.18h4.97v.73H5.06S2 6.84 2 11.81c0 4.97 2.7 4.79 2.7 4.79h1.61v-2.27s-.09-2.7 2.65-2.7h4.63v-.73h-7V7.05h7V4.14S16.97 2 12 2zm-.94 1.5c.44 0 .8.36.8.8s-.36.8-.8.8-.8-.36-.8-.8.36-.8.8-.8z" />
            </svg>
        ),
    },
    OpenAI: {
        color: '#10A37F',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#10A37F]">
                <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 14h-2v-4h2zm0-6h-2V8h2z" />
            </svg>
        ),
    },
    FastAPI: {
        color: '#009688',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#009688]">
                <path d="M12 2L4 14h6l-2 8 10-12h-6l2-8z" />
            </svg>
        ),
    },
    'Chart.js': {
        color: '#FF6384',
        icon: (
            <svg viewBox="0 0 24 24" className="h-4 w-4 shrink-0 fill-[#FF6384]">
                <path d="M3 3v18h18V3H3zm4 14H5v-6h2v6zm5 0h-2V7h2v10zm5 0h-2v-4h2v4z" />
            </svg>
        ),
    },
};

export default function TechBadge({ name, icon, techStack = [], compact = false }) {
    // 1. Resolve custom icon / SVG from techStack list if available
    let resolvedIcon = icon;
    if (!resolvedIcon && Array.isArray(techStack) && techStack.length > 0) {
        const match = techStack.find(
            (t) => (t?.title || '').trim().toLowerCase() === (name || '').trim().toLowerCase()
        );
        if (match && match.image_or_icon) {
            resolvedIcon = match.image_or_icon;
        }
    }

    const iconSizeClass = compact ? 'h-3.5 w-3.5 shrink-0 object-contain' : 'h-4 w-4 shrink-0 object-contain';

    // 2. Render icon
    let iconElement = null;
    if (resolvedIcon) {
        iconElement = (
            <TechIcon
                name={name}
                icon={resolvedIcon}
                className={iconSizeClass}
            />
        );
    } else {
        const key = Object.keys(TECH_ICONS).find(
            (k) => k.toLowerCase() === (name || '').trim().toLowerCase()
        );
        const config = (key ? TECH_ICONS[key] : null) || TECH_ICONS[name];
        if (config && config.icon) {
            iconElement = config.icon;
        } else {
            // Check TechIcon built-in brand icons
            iconElement = (
                <TechIcon
                    name={name}
                    className={iconSizeClass}
                />
            );
        }
    }

    return (
        <BorderGlow
            edgeSensitivity={30}
            glowColor="40 80 80"
            backgroundColor="#0d1322"
            borderRadius={compact ? 10 : 14}
            glowRadius={compact ? 20 : 30}
            glowIntensity={1.0}
            coneSpread={25}
            animated={false}
            colors={[
                '#c084fc',
                '#f472b6',
                '#38bdf8',
            ]}
            contentClassName={compact ? 'flex items-center gap-1.5 px-2.5 py-1 relative overflow-hidden z-[1]' : 'flex items-center gap-2 px-3.5 py-2 relative overflow-hidden z-[1]'}
            className="group inline-flex items-center cursor-default shadow-md transition-all duration-300 hover:scale-105"
        >
            {iconElement}
            <span className={compact ? 'font-sans text-[11px] font-medium text-slate-200' : 'font-sans text-xs sm:text-sm font-medium text-slate-200'}>
                {name}
            </span>
        </BorderGlow>
    );
}


import { useState, useEffect, useCallback } from 'react';
import { getPortfolio } from '../services/api';

export function usePortfolioData() {
    const [data, setData] = useState({
        stats: { projects_count: 0, certificates_count: 0, awards_count: 0 },
        education: [],
        experience: [],
        projects: [],
        certificates: [],
        achievements: [],
        tech_stack: [],
        settings: {},
    });
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const fetchData = useCallback(async () => {
        setLoading(true);
        setError(null);
        try {
            const res = await getPortfolio();
            if (res && res.data) {
                setData(res.data);
            }
        } catch (err) {
            console.error('Failed to fetch portfolio data:', err);
            setError(
                err?.response?.data?.message ||
                    err.message ||
                    'Gagal memuat data portofolio.',
            );
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        void fetchData();
    }, [fetchData]);

    return { data, loading, error, refetch: fetchData };
}

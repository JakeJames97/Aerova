import api from '@/lib/axios';
import type {DashboardData} from "@/types/dashboard.ts";

export async function getDashboardData(): Promise<DashboardData> {
  const res = await api.get<DashboardData>('/dashboard');
  return res.data;
}

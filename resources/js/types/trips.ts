import type {Destination} from "@/types/destinations.ts";

  export const TRIP_STATUSES = ['planned', 'in_progress', 'completed'] as const;
export type TripStatus = (typeof TRIP_STATUSES)[number];

export interface Trip {
  id: string;
  name: string;
  description: string | null;
  start_date: string;
  end_date: string;
  status: TripStatus;
  created_at: string;
  destinations_count: number;
  destinations?: Destination[];
}

export interface TripPayload {
  name: string;
  description: string | null;
  start_date: string;
  end_date: string;
  status: TripStatus;
}

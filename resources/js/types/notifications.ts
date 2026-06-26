export interface NotificationData {
  type: 'trip_liked' | 'trip_cloned' | 'trip_status_changed';
  trip_id: string;
  trip_name: string;
  message: string;
  status?: string;
}

export interface Notification {
  id: string;
  type: string | null;
  data: NotificationData;
  read_at: string | null;
  created_at: string;
}

export interface MarkAsReadResponse {
  data: {
    unread_count: number;
  }
}

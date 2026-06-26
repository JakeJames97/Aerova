import dayjs from 'dayjs';

import relativeTime from 'dayjs/plugin/relativeTime';

dayjs.extend(relativeTime);

export function formatDateRange(start: string, end: string): string {
  const startDate = dayjs(start);
  const endDate = dayjs(end);

  if (startDate.isSame(endDate, 'month')) {
    return `${startDate.format('D')}–${endDate.format('D MMM YYYY')}`;
  }
  if (startDate.isSame(endDate, 'year')) {
    return `${startDate.format('D MMM')} – ${endDate.format('D MMM YYYY')}`;
  }
  return `${startDate.format('D MMM YYYY')} – ${endDate.format('D MMM YYYY')}`;
}

export function formatRelativeTime(iso: string): string {
  return dayjs(iso).fromNow();
}

export { dayjs };

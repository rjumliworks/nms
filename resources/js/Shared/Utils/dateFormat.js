export function formatLongDate(dateStr){
    if(!dateStr) return '-';
    const [year, month, day] = String(dateStr).split('-').map(Number);
    if(!year || !month || !day) return dateStr;
    return new Date(year, month - 1, day).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
}

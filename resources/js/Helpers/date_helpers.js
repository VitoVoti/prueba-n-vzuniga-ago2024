// Obtenido de https://stackoverflow.com/a/59443110
// Parse timestamp in YYYY-MM-DD format as local
function parseISOLocal(s) {
    let [y, m, d] = s.split(/\D/);
    return new Date(y, --m, d);
}
  
// Format date as YYYY-MM-DD local
function formatISOLocal(d) {
    let z = n => (n<10?'0':'') + n;
    return d.getFullYear() + '-' + z(d.getMonth()+1) + '-' + z(d.getDate());
}
  
export { parseISOLocal, formatISOLocal }
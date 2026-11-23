# Proyek Kedai Kopi - Kopi Kenangan

Deployment web Kopi Kenangan (PHP, MySQL, Docker Compose, ProxySQL) di Ubuntu VM (192.168.129.131).

## Layanan & Akses
- Web x2: http://192.168.129.131:32768/ dan http://192.168.129.131:32769/
- ProxySQL: port 6033 pada jaringan kedaikopi-net
- Monitoring & Backup: cek CPU/RAM/disk (df -h) serta backup SQL harian jam 02:00 ke backups/

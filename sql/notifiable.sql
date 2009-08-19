# Connection: localhost
# Host: localhost
# Saved: 2004-05-11 00:24:19
# 
SELECT l.disease_name, count(l.disease_id)
FROM m_consult_disease_notifiable c, m_lib_disease_notifiable l
WHERE l.disease_id = c.disease_id 
GROUP BY c.disease_id

SELECT CONCAT(LPAD(hour(consult_timestamp),2,'0'),'00'), count(consult_id)
FROM `m_consult`
group by hour(consult_timestamp)

SELECT week(consult_date), avg(elapsed_time), count(consult_id)
FROM `m_consult` group by week(consult_date)